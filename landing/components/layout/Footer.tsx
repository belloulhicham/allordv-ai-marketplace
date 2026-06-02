import Link from "next/link";

const columns = {
  patients: {
    title: "Patients",
    links: [
      { label: "Trouver un médecin", href: "/medecins" },
      { label: "Spécialités", href: "/specialites" },
      { label: "Téléconsultation", href: "/teleconsultation" },
      { label: "Luna IA", href: "/luna" },
      { label: "Comment ça marche", href: "/comment-ca-marche" },
    ],
  },
  medecins: {
    title: "Médecins",
    links: [
      { label: "Espace médecin", href: "/medecins/espace" },
      { label: "Tarifs", href: "/medecins/tarifs" },
      { label: "Fonctionnalités", href: "/medecins/fonctionnalites" },
      { label: "Témoignages", href: "/temoignages" },
      { label: "Support médecin", href: "/medecins/support" },
    ],
  },
  specialites: {
    title: "Spécialités",
    links: [
      { label: "Médecin généraliste", href: "/medecins/generaliste/casablanca" },
      { label: "Dentiste", href: "/medecins/dentiste/casablanca" },
      { label: "Cardiologue", href: "/medecins/cardiologue/casablanca" },
      { label: "Pédiatre", href: "/medecins/pediatre/casablanca" },
      { label: "Gynécologue", href: "/medecins/gynecologue/casablanca" },
      { label: "Voir toutes", href: "/specialites" },
    ],
  },
  legal: {
    title: "Légal",
    links: [
      { label: "Mentions légales", href: "/mentions-legales" },
      { label: "Confidentialité", href: "/confidentialite" },
      { label: "CGU", href: "/cgu" },
      { label: "Contact", href: "/contact" },
      { label: "FAQ", href: "/faq" },
    ],
  },
};

function IconFacebook() {
  return (
    <svg aria-hidden="true" className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
      <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
    </svg>
  );
}
function IconInstagram() {
  return (
    <svg aria-hidden="true" className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
      <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
      <circle cx="12" cy="12" r="4" />
      <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" />
    </svg>
  );
}
function IconLinkedIn() {
  return (
    <svg aria-hidden="true" className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
      <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z" />
      <circle cx="4" cy="4" r="2" />
    </svg>
  );
}
function IconX() {
  return (
    <svg aria-hidden="true" className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
    </svg>
  );
}

export default function Footer() {
  return (
    <footer className="bg-gray-900 text-gray-300" aria-label="Pied de page AllordV">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
        {/* 5-column grid */}
        <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 mb-12">
          {/* Col 1 — Brand */}
          <div className="lg:col-span-1">
            <Link href="/" className="inline-block mb-4" aria-label="AllordV accueil">
              <span className="text-2xl font-bold text-white">AllordV</span>
            </Link>
            <p className="text-sm text-gray-400 mb-6 leading-relaxed">
              La santé intelligemment gérée au Maroc grâce à l&apos;IA.
            </p>
            <div className="flex flex-col gap-2">
              <span className="inline-flex items-center gap-2 text-xs bg-blue-900/50 text-blue-300 px-3 py-1.5 rounded-full w-fit">
                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Conforme CNDP
              </span>
              <span className="inline-flex items-center gap-2 text-xs bg-green-900/50 text-green-300 px-3 py-1.5 rounded-full w-fit">
                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Données sécurisées
              </span>
              <span className="inline-flex items-center gap-2 text-xs bg-purple-900/50 text-purple-300 px-3 py-1.5 rounded-full w-fit">
                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24" aria-hidden="true">
                  <circle cx="12" cy="12" r="10" /><path d="M12 6v6l4 2" />
                </svg>
                Disponible 24h/7j
              </span>
            </div>
          </div>

          {/* Col 2 — Patients */}
          <nav aria-label="Liens patients">
            <h3 className="text-white font-semibold mb-4">{columns.patients.title}</h3>
            <ul className="space-y-2">
              {columns.patients.links.map((link) => (
                <li key={link.href}>
                  <Link href={link.href} className="text-sm text-gray-400 hover:text-white transition-colors">
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>

          {/* Col 3 — Médecins */}
          <nav aria-label="Liens médecins">
            <h3 className="text-white font-semibold mb-4">{columns.medecins.title}</h3>
            <ul className="space-y-2">
              {columns.medecins.links.map((link) => (
                <li key={link.href}>
                  <Link href={link.href} className="text-sm text-gray-400 hover:text-white transition-colors">
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>

          {/* Col 4 — Spécialités */}
          <nav aria-label="Spécialités médicales">
            <h3 className="text-white font-semibold mb-4">{columns.specialites.title}</h3>
            <ul className="space-y-2">
              {columns.specialites.links.map((link) => (
                <li key={link.href}>
                  <Link href={link.href} className="text-sm text-gray-400 hover:text-white transition-colors">
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>

          {/* Col 5 — Légal */}
          <nav aria-label="Liens légaux">
            <h3 className="text-white font-semibold mb-4">{columns.legal.title}</h3>
            <ul className="space-y-2">
              {columns.legal.links.map((link) => (
                <li key={link.href}>
                  <Link href={link.href} className="text-sm text-gray-400 hover:text-white transition-colors">
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>
        </div>

        {/* Bottom bar */}
        <div className="border-t border-gray-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p className="text-sm text-gray-500">
            © {new Date().getFullYear()} AllordV. Tous droits réservés. 🇲🇦
          </p>
          <div className="flex items-center gap-4">
            <Link href="https://facebook.com" aria-label="AllordV sur Facebook" className="text-gray-500 hover:text-white transition-colors">
              <IconFacebook />
            </Link>
            <Link href="https://instagram.com" aria-label="AllordV sur Instagram" className="text-gray-500 hover:text-white transition-colors">
              <IconInstagram />
            </Link>
            <Link href="https://linkedin.com" aria-label="AllordV sur LinkedIn" className="text-gray-500 hover:text-white transition-colors">
              <IconLinkedIn />
            </Link>
            <Link href="https://x.com" aria-label="AllordV sur X (Twitter)" className="text-gray-500 hover:text-white transition-colors">
              <IconX />
            </Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
