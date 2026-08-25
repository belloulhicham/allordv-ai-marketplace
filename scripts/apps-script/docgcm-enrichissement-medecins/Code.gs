/**
 * ===================================================================
 * ENRICHISSEMENT BASE MÉDECINS v2 - Places API + Gemini API + Sheets
 * DocGCM - Hicham
 * ===================================================================
 *
 * NOUVEAU DANS CETTE VERSION :
 * En plus de la recherche par nom (v1), ce script découvre les
 * cliniques/polycliniques/centres médicaux par ville via Places API,
 * va lire leur site web, et utilise l'API Gemini pour EXTRAIRE la
 * liste des médecins qui y travaillent (nom, spécialité, téléphone,
 * email) — peu importe la structure HTML du site, contrairement à un
 * script de scraping classique qui doit être codé site par site.
 *
 * 3 ONGLETS :
 * - "Médecins"            : v1, recherche nominative (déjà en place)
 * - "Cliniques"           : liste des cliniques découvertes + leur site
 * - "Médecins - Cliniques": médecins extraits des sites de cliniques
 *
 * ===================================================================
 * INSTALLATION
 * ===================================================================
 * 1. Remplace TOUT le contenu de ton script Apps Script existant par
 *    ce fichier (il inclut aussi les fonctions de la v1, rien n'est
 *    perdu)
 * 2. Project Settings > Script Properties > vérifie que tu as :
 *    - GOOGLE_PLACES_API_KEY (déjà fait normalement)
 *    - GEMINI_API_KEY (nouveau — clé API Gemini, gratuite sur aistudio.google.com/apikey)
 * 3. Actualise le Sheet (F5), menu "🩺 Enrichissement Médecins"
 * 4. "Créer/réinitialiser les feuilles" (crée les 3 onglets)
 * 5. "1. Découvrir les cliniques" — remplit l'onglet "Cliniques"
 *    (villes ciblées configurables dans VILLES ci-dessous)
 * 6. "2. Extraire médecins des cliniques (lot suivant)" — relance
 *    plusieurs fois jusqu'à ce que toutes les cliniques soient traitées
 *
 * COÛT (en plus de Places API, voir v1) :
 * Gemini Flash (modèle utilisé ici) a un palier gratuit généreux
 * (voir ai.google.dev/pricing) ; au-delà, ça reste très économique
 * pour quelques centaines de cliniques.
 *
 * RAPPEL FIABILITÉ :
 * L'IA peut se tromper ou mal lire une page mal structurée. Chaque
 * médecin extrait doit être vérifié avant tout envoi ou publication
 * (statut "Trouvé"/"Ambigu" ne remplace pas une vérification humaine
 * ni l'accord du médecin pour un profil public).
 * ===================================================================
 */

// ------------------- CONFIGURATION -------------------
const SHEET_MEDECINS = 'Médecins';
const SHEET_CLINIQUES = 'Cliniques';
const SHEET_MEDECINS_CLINIQUES = 'Médecins - Cliniques';

const BATCH_SIZE = 40;          // v1 : médecins traités par exécution
const BATCH_SIZE_CLINIQUES = 15; // v2 : cliniques traitées par exécution (limite par le temps d'exécution 6 min)
const PAUSE_MS = 250;

const VILLES = ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Tanger', 'Agadir', 'Meknès', 'Oujda'];
const TYPES_ETABLISSEMENT = ['clinique', 'polyclinique', 'centre médical', 'cabinet médical', 'cabinet de médecin'];

const GEMINI_MODEL = 'gemini-2.5-flash'; // rapide et économique, adapté à de l'extraction en volume

const HEADERS_MEDECINS = [
  'Nom', 'Prénom', 'Spécialité', 'Ville',
  'Statut', 'Téléphone', 'Adresse', 'Nom trouvé (Places)', 'Lien Maps', 'Date traitement'
];
const HEADERS_CLINIQUES = [
  'Nom clinique', 'Ville', 'Type', 'Website', 'PlaceId', 'Statut', 'Date découverte'
];
const HEADERS_MEDECINS_CLINIQUES = [
  'Nom', 'Prénom', 'Spécialité', 'Téléphone', 'Email', 'Clinique source', 'Ville', 'Date extraction'
];

// ------------------- MENU -------------------
function onOpen() {
  SpreadsheetApp.getUi()
    .createMenu('🩺 Enrichissement Médecins')
    .addItem('Créer/réinitialiser les feuilles', 'setupSheets')
    .addSeparator()
    .addItem('Lancer l\'enrichissement par nom (v1, lot suivant)', 'enrichirMedecins')
    .addSeparator()
    .addItem('1. Découvrir les cliniques', 'decouvrirCliniques')
    .addItem('2. Extraire médecins des cliniques (lot suivant)', 'extraireDepuisCliniques')
    .addSeparator()
    .addItem('Activer le déclencheur quotidien (v1, 8h)', 'creerDeclencheurQuotidien')
    .addItem('Désactiver tous les déclencheurs', 'supprimerDeclencheurs')
    .addToUi();
}

function setupSheets() {
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  creerOuViderFeuille(ss, SHEET_MEDECINS, HEADERS_MEDECINS);
  creerOuViderFeuille(ss, SHEET_CLINIQUES, HEADERS_CLINIQUES);
  creerOuViderFeuille(ss, SHEET_MEDECINS_CLINIQUES, HEADERS_MEDECINS_CLINIQUES);
  SpreadsheetApp.getUi().alert(
    'Feuilles prêtes.\n\n"Médecins" : colle ta liste nominative (Nom/Prénom/Spécialité/Ville).\n' +
    '"Cliniques" et "Médecins - Cliniques" se remplissent automatiquement via le menu.'
  );
}

function creerOuViderFeuille(ss, nom, headers) {
  let sheet = ss.getSheetByName(nom);
  if (!sheet) sheet = ss.insertSheet(nom);
  if (sheet.getLastRow() === 0) {
    sheet.getRange(1, 1, 1, headers.length).setValues([headers]).setFontWeight('bold');
    sheet.setFrozenRows(1);
    sheet.autoResizeColumns(1, headers.length);
  }
  return sheet;
}

// ===================================================================
// V1 - RECHERCHE NOMINATIVE (inchangé)
// ===================================================================
function enrichirMedecins() {
  const apiKey = getProp('GOOGLE_PLACES_API_KEY');
  if (!apiKey) return alerte('Clé GOOGLE_PLACES_API_KEY manquante dans Script Properties.');

  const ss = SpreadsheetApp.getActiveSpreadsheet();
  const sheet = ss.getSheetByName(SHEET_MEDECINS);
  if (!sheet) return alerte('Feuille "Médecins" introuvable. Lance d\'abord "Créer/réinitialiser les feuilles".');

  const data = sheet.getDataRange().getValues();
  const statutCol = HEADERS_MEDECINS.indexOf('Statut');
  let traites = 0;

  for (let i = 1; i < data.length && traites < BATCH_SIZE; i++) {
    const row = data[i];
    const [nom, prenom, specialite, ville] = row;
    if ((!nom && !prenom) || row[statutCol]) continue;

    const resultat = chercherMedecin(nom, prenom, specialite, ville, apiKey);
    sheet.getRange(i + 1, statutCol + 1, 1, HEADERS_MEDECINS.length - statutCol).setValues([[
      resultat.statut, resultat.telephone, resultat.adresse, resultat.nomTrouve, resultat.lienMaps, new Date()
    ]]);
    traites++;
    Utilities.sleep(PAUSE_MS);
  }
  alerte(traites + ' médecin(s) traité(s) dans ce lot (recherche nominative).');
}

function chercherMedecin(nom, prenom, specialite, ville, apiKey) {
  const requete = ['Dr', prenom, nom, specialite, ville, 'Maroc'].filter(Boolean).join(' ');
  const searchUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query='
    + encodeURIComponent(requete) + '&key=' + apiKey;

  let searchData;
  try {
    searchData = JSON.parse(UrlFetchApp.fetch(searchUrl, { muteHttpExceptions: true }).getContentText());
  } catch (e) {
    return { statut: 'Erreur recherche', telephone: '', adresse: '', nomTrouve: '', lienMaps: '' };
  }
  if (!searchData.results || searchData.results.length === 0) {
    return { statut: 'Non trouvé', telephone: '', adresse: '', nomTrouve: '', lienMaps: '' };
  }

  const top = searchData.results[0];
  const detailsUrl = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' + top.place_id
    + '&fields=name,formatted_phone_number,formatted_address,url&key=' + apiKey;

  let details = {};
  try {
    details = JSON.parse(UrlFetchApp.fetch(detailsUrl, { muteHttpExceptions: true }).getContentText()).result || {};
  } catch (e) {}

  const nomTrouve = details.name || top.name || '';
  return {
    statut: nomCorrespond(nom, prenom, nomTrouve) ? 'Trouvé' : 'Ambigu à vérifier',
    telephone: details.formatted_phone_number || '',
    adresse: details.formatted_address || top.formatted_address || '',
    nomTrouve: nomTrouve,
    lienMaps: details.url || ''
  };
}

function nomCorrespond(nom, prenom, nomTrouve) {
  if (!nomTrouve) return false;
  const norm = s => (s || '').toString().toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
  const cible = norm(nomTrouve);
  return Boolean((nom && cible.includes(norm(nom))) || (prenom && cible.includes(norm(prenom))));
}

// ===================================================================
// V2 - DÉCOUVERTE DE CLINIQUES
// ===================================================================
function decouvrirCliniques() {
  const apiKey = getProp('GOOGLE_PLACES_API_KEY');
  if (!apiKey) return alerte('Clé GOOGLE_PLACES_API_KEY manquante dans Script Properties.');

  const ss = SpreadsheetApp.getActiveSpreadsheet();
  const sheet = ss.getSheetByName(SHEET_CLINIQUES) || creerOuViderFeuille(ss, SHEET_CLINIQUES, HEADERS_CLINIQUES);
  const existants = new Set(sheet.getDataRange().getValues().slice(1).map(r => r[4])); // PlaceId déjà connus

  let ajoutes = 0;
  const erreursApi = new Set();
  VILLES.forEach(ville => {
    TYPES_ETABLISSEMENT.forEach(type => {
      const requete = type + ' ' + ville + ' Maroc';
      const url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query='
        + encodeURIComponent(requete) + '&key=' + apiKey;

      let data;
      try {
        data = JSON.parse(UrlFetchApp.fetch(url, { muteHttpExceptions: true }).getContentText());
      } catch (e) { return; }

      if (data.status && data.status !== 'OK' && data.status !== 'ZERO_RESULTS') {
        erreursApi.add(data.status + (data.error_message ? ' : ' + data.error_message : ''));
        return;
      }

      (data.results || []).forEach(place => {
        if (existants.has(place.place_id)) return;
        existants.add(place.place_id);

        const detailsUrl = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' + place.place_id
          + '&fields=name,website&key=' + apiKey;
        let website = '';
        try {
          const d = JSON.parse(UrlFetchApp.fetch(detailsUrl, { muteHttpExceptions: true }).getContentText()).result;
          website = (d && d.website) || '';
        } catch (e) {}

        sheet.appendRow([place.name, ville, type, website, place.place_id, website ? 'Non traité' : 'Sans site', new Date()]);
        ajoutes++;
        Utilities.sleep(PAUSE_MS);
      });
    });
  });

  if (ajoutes === 0 && erreursApi.size > 0) {
    alerte('0 clinique découverte — erreur API Places :\n' + Array.from(erreursApi).join('\n') +
      '\n\nVérifie que "Places API" (legacy) est activée et que la facturation est liée sur ton projet Google Cloud.');
    return;
  }
  alerte(ajoutes + ' clinique(s)/centre(s) découvert(s) et ajouté(s).');
}

// ===================================================================
// V2 - EXTRACTION DES MÉDECINS PAR IA (Gemini API)
// ===================================================================
function extraireDepuisCliniques() {
  const geminiKey = getProp('GEMINI_API_KEY');
  if (!geminiKey) return alerte('Clé GEMINI_API_KEY manquante dans Script Properties.');

  const ss = SpreadsheetApp.getActiveSpreadsheet();
  const sheetCliniques = ss.getSheetByName(SHEET_CLINIQUES);
  const sheetMedecins = ss.getSheetByName(SHEET_MEDECINS_CLINIQUES) || creerOuViderFeuille(ss, SHEET_MEDECINS_CLINIQUES, HEADERS_MEDECINS_CLINIQUES);
  if (!sheetCliniques) return alerte('Feuille "Cliniques" introuvable. Lance d\'abord "1. Découvrir les cliniques".');

  const data = sheetCliniques.getDataRange().getValues();
  const statutCol = HEADERS_CLINIQUES.indexOf('Statut');
  const websiteCol = HEADERS_CLINIQUES.indexOf('Website');
  const dejaVus = new Set(sheetMedecins.getDataRange().getValues().slice(1).map(r => (r[3] || r[0] + r[1])));

  let traites = 0;
  for (let i = 1; i < data.length && traites < BATCH_SIZE_CLINIQUES; i++) {
    const row = data[i];
    if (row[statutCol] !== 'Non traité' || !row[websiteCol]) continue;

    const nomClinique = row[0], ville = row[1], website = row[websiteCol];
    let medecins = [];
    try {
      const texte = recupererTexteBrut(website);
      medecins = appellerGeminiExtraction(texte, geminiKey);
    } catch (e) {
      sheetCliniques.getRange(i + 1, statutCol + 1).setValue('Erreur : ' + e.message);
      traites++;
      continue;
    }

    medecins.forEach(m => {
      const cle = m.telephone || m.email || (m.nom + m.prenom);
      if (dejaVus.has(cle)) return;
      dejaVus.add(cle);
      sheetMedecins.appendRow([m.nom || '', m.prenom || '', m.specialite || '', m.telephone || '', m.email || '', nomClinique, ville, new Date()]);
    });

    sheetCliniques.getRange(i + 1, statutCol + 1).setValue('Traité (' + medecins.length + ' médecin(s))');
    traites++;
    Utilities.sleep(PAUSE_MS);
  }
  alerte(traites + ' clinique(s) traitée(s) dans ce lot.');
}

// Récupère le texte brut (sans balises HTML) d'une page web
function recupererTexteBrut(url) {
  const response = UrlFetchApp.fetch(url, { muteHttpExceptions: true, followRedirects: true });
  let html = response.getContentText();
  html = html.replace(/<script[\s\S]*?<\/script>/gi, ' ')
             .replace(/<style[\s\S]*?<\/style>/gi, ' ')
             .replace(/<[^>]+>/g, ' ')
             .replace(/&nbsp;/g, ' ')
             .replace(/\s+/g, ' ')
             .trim();
  return html.substring(0, 12000); // limite pour rester raisonnable en tokens
}

// Appelle l'API Gemini pour extraire les médecins d'un texte de page web
function appellerGeminiExtraction(texte, apiKey) {
  const prompt = 'Voici le contenu texte d\'une page web d\'un établissement médical au Maroc. ' +
    'Extrais la liste des médecins mentionnés avec leurs informations disponibles. ' +
    'Réponds UNIQUEMENT avec un tableau JSON valide, sans aucun texte avant ou après, format exact : ' +
    '[{"nom":"...","prenom":"...","specialite":"...","telephone":"...","email":"..."}]. ' +
    'Si une information n\'est pas disponible, mets une chaîne vide "". ' +
    'Si aucun médecin n\'est identifiable dans ce texte, réponds [].\n\nContenu de la page:\n\n' + texte;

  const url = 'https://generativelanguage.googleapis.com/v1beta/models/' + GEMINI_MODEL
    + ':generateContent?key=' + apiKey;

  const response = UrlFetchApp.fetch(url, {
    method: 'post',
    contentType: 'application/json',
    payload: JSON.stringify({
      contents: [{ parts: [{ text: prompt }] }],
      generationConfig: { maxOutputTokens: 1500, temperature: 0 }
    }),
    muteHttpExceptions: true
  });

  const data = JSON.parse(response.getContentText());
  const partie = data.candidates && data.candidates[0]
    && data.candidates[0].content && data.candidates[0].content.parts
    && data.candidates[0].content.parts[0];
  if (!partie || !partie.text) return [];

  let texteReponse = partie.text.trim();
  texteReponse = texteReponse.replace(/^```json\s*/i, '').replace(/```\s*$/, '');
  try {
    return JSON.parse(texteReponse);
  } catch (e) {
    return [];
  }
}

// ------------------- DÉCLENCHEURS -------------------
function creerDeclencheurQuotidien() {
  supprimerDeclencheurs();
  ScriptApp.newTrigger('enrichirMedecins').timeBased().everyDays(1).atHour(8).create();
  alerte('Déclencheur quotidien activé pour la recherche nominative (v1).');
}

function supprimerDeclencheurs() {
  ScriptApp.getProjectTriggers().forEach(t => {
    if (['enrichirMedecins', 'decouvrirCliniques', 'extraireDepuisCliniques'].includes(t.getHandlerFunction())) {
      ScriptApp.deleteTrigger(t);
    }
  });
}

// ------------------- HELPERS -------------------
function getProp(nom) {
  return PropertiesService.getScriptProperties().getProperty(nom);
}
function alerte(message) {
  SpreadsheetApp.getUi().alert(message);
}
