import HeroSection from "@/components/sections/HeroSection";
import TestimonialsSection from "@/components/sections/TestimonialsSection";

export default function Home() {
  return (
    <main>
      <HeroSection />
      {/* Autres sections à venir (spécialités, pricing, HowItWorks...) */}
      <TestimonialsSection />
    </main>
  );
}
