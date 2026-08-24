# DocGCM - Enrichissement base médecins (Google Apps Script)

Script Google Apps Script (bound à un Google Sheet), sans lien avec l'application
Laravel de ce dépôt. Conservé ici pour versionnement/backup.

## Installation

1. Ouvre le Google Sheet cible, `Extensions > Apps Script`.
2. Colle le contenu de `Code.gs` (remplace tout le contenu existant — inclut
   aussi les fonctions v1, rien n'est perdu).
3. `Project Settings > Script Properties`, ajoute :
   - `GOOGLE_PLACES_API_KEY` — clé API Google Places
   - `ANTHROPIC_API_KEY` — clé API Claude (console.anthropic.com)
4. Actualise le Sheet (F5), menu **🩺 Enrichissement Médecins**.
5. "Créer/réinitialiser les feuilles" (crée les 3 onglets : Médecins,
   Cliniques, Médecins - Cliniques).
6. "1. Découvrir les cliniques", puis "2. Extraire médecins des cliniques
   (lot suivant)" — relance plusieurs fois jusqu'à ce que toutes les
   cliniques soient traitées.

Voir l'en-tête de `Code.gs` pour le détail (coût, fiabilité, etc.).
