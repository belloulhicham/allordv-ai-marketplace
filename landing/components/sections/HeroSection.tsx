import Link from "next/link";

const stats = [
  { label: "Disponible 24h/24", icon: "clock" },
  { label: "RDV en 30 secondes", icon: "bolt" },
  { label: "Conforme CNDP", icon: "shield" },
  { label: "IA médicale en Darija", icon: "microphone" },
] as const;

function StatIcon({ icon }: { icon: string }) {
  switch (icon) {
    case "clock":
      return (
        <svg aria-hidden="true" className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" /><path d="M12 6v6l4 2" />
        </svg>
      );
    case "bolt":
      return (
        <svg aria-hidden="true" className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
          <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
        </svg>
      );
    case "shield":
      return (
        <svg aria-hidden="true" className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
        </svg>
      );
    case "microphone":
      return (
        <svg aria-hidden="true" className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
          <rect x="9" y="2" width="6" height="11" rx="3" /><path d="M5 10a7 7 0 0014 0M12 19v3M8 22h8" />
        </svg>
      );
    default:
      return null;
  }
}

export default function HeroSection() {
  return (
    <section
      aria-label="Accueil AllordV"
      className="relative bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500 text-white overflow-hidden"
    >
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.1),transparent_60%)]" aria-hidden="true" />

      <div className="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div className="max-w-3xl">
          <span className="inline-block bg-white/20 text-white text-sm font-medium px-3 py-1 rounded-full mb-6">
            🇲🇦 La santé intelligente au Maroc
          </span>
          <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
            Votre médecin en{" "}
            <span className="text-cyan-200">30 secondes</span>
          </h1>
          <p className="text-lg sm:text-xl text-blue-100 mb-8 max-w-2xl">
            Prenez rendez-vous avec des médecins vérifiés à Casablanca, Rabat et
            dans toutes les villes du Maroc. Luna, notre IA, vous répond en
            Darija 24h/24.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 mb-14">
            <Link
              href="/medecins"
              className="inline-flex items-center justify-center gap-2 bg-white text-blue-700 font-semibold px-8 py-4 rounded-xl hover:bg-blue-50 transition-colors shadow-lg"
              aria-label="Prendre un rendez-vous médical"
            >
              Prendre RDV gratuitement
              <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24" aria-hidden="true">
                <path d="M5 12h14M12 5l7 7-7 7" />
              </svg>
            </Link>
            <Link
              href="/medecins/inscription"
              className="inline-flex items-center justify-center gap-2 bg-white/10 border border-white/30 text-white font-semibold px-8 py-4 rounded-xl hover:bg-white/20 transition-colors"
              aria-label="Inscrire votre cabinet médical"
            >
              Inscrire mon cabinet
            </Link>
          </div>
        </div>

        <ul
          className="grid grid-cols-2 lg:grid-cols-4 gap-4"
          aria-label="Points forts AllordV"
        >
          {stats.map((stat) => (
            <li
              key={stat.label}
              className="flex items-center gap-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-4"
            >
              <span className="flex-shrink-0 text-cyan-200">
                <StatIcon icon={stat.icon} />
              </span>
              <span className="text-sm font-semibold leading-tight">{stat.label}</span>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}
