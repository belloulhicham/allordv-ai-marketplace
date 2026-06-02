const testimonials = [
  {
    name: "Dr. Karim Benjelloun",
    role: "Cardiologue",
    clinic: "Clinique Al Farabi, Casablanca",
    rating: 5,
    type: "doctor" as const,
    text: "AllordV a réduit mes no-shows de 40%. L'IA diagnostique me fait gagner un temps précieux.",
    initials: "KB",
  },
  {
    name: "Dr. Fatima Zahra Alami",
    role: "Pédiatre",
    clinic: "Cabinet privé, Rabat",
    rating: 5,
    type: "doctor" as const,
    text: "Mes patients prennent RDV la nuit avec Luna. Je reçois les demandes le matin.",
    initials: "FA",
  },
  {
    name: "Dr. Youssef Chahid",
    role: "Médecin généraliste",
    clinic: "Centre médical Maarif, Casablanca",
    rating: 5,
    type: "doctor" as const,
    text: "Le support en Darija est un vrai plus. La téléconsultation HD évite les déplacements.",
    initials: "YC",
  },
  {
    name: "Samira El Idrissi",
    role: "Patiente",
    clinic: "Casablanca",
    rating: 5,
    type: "patient" as const,
    text: "RDV pris en 30s à 23h un dimanche. Luna m'a répondu en Darija. Confirmation WhatsApp immédiate.",
    initials: "SE",
  },
];

function StarRating({ rating }: { rating: number }) {
  return (
    <div className="flex gap-0.5" aria-label={`Note : ${rating} étoiles sur 5`}>
      {Array.from({ length: 5 }).map((_, i) => (
        <svg
          key={i}
          aria-hidden="true"
          className={`w-4 h-4 ${i < rating ? "text-yellow-400" : "text-gray-200"}`}
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
      ))}
    </div>
  );
}

const avatarColors: Record<string, string> = {
  KB: "bg-blue-600",
  FA: "bg-rose-500",
  YC: "bg-emerald-600",
  SE: "bg-violet-500",
};

export default function TestimonialsSection() {
  return (
    <section aria-labelledby="testimonials-heading" className="py-20 bg-gray-50">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 id="testimonials-heading" className="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            Ils font confiance à AllordV
          </h2>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Médecins et patients partagent leur expérience avec notre plateforme.
          </p>
        </div>

        <ul className="grid grid-cols-1 md:grid-cols-2 gap-6" aria-label="Témoignages">
          {testimonials.map((t) => (
            <li
              key={t.name}
              className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col gap-4"
            >
              <div className="flex items-start justify-between gap-4">
                <div className="flex items-center gap-3">
                  {/* Avatar rond */}
                  <div
                    className={`w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0 ${avatarColors[t.initials] ?? "bg-gray-500"}`}
                    aria-hidden="true"
                  >
                    {t.initials}
                  </div>
                  <div>
                    <p className="font-semibold text-gray-900 text-sm">{t.name}</p>
                    <p className="text-sm text-gray-500">{t.role}</p>
                    <p className="text-xs text-gray-400">{t.clinic}</p>
                  </div>
                </div>
                {/* Badge vérifié */}
                <span
                  className={`flex-shrink-0 text-xs font-medium px-2.5 py-1 rounded-full ${
                    t.type === "doctor"
                      ? "bg-blue-100 text-blue-700"
                      : "bg-green-100 text-green-700"
                  }`}
                  aria-label={t.type === "doctor" ? "Médecin vérifié" : "Patient vérifié"}
                >
                  {t.type === "doctor" ? "✓ Médecin vérifié" : "✓ Patient vérifié"}
                </span>
              </div>

              <StarRating rating={t.rating} />

              <blockquote className="text-gray-700 text-sm leading-relaxed italic">
                &ldquo;{t.text}&rdquo;
              </blockquote>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}
