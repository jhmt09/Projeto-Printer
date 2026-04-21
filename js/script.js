const slides = [
  {
    src: 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
    alt: 'Equipe técnica industrial em manutenção'
  },
  {
    src: 'https://images.unsplash.com/photo-1531058020387-3be344556be6?auto=format&fit=crop&w=1200&q=80',
    alt: 'Máquina industrial com painel eletrônico'
  },
  {
    src: 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1200&q=80',
    alt: 'Engrenagens metálicas e manutenção'
  }
];

const carousel = document.getElementById('carousel');
const prevButton = document.getElementById('prevButton');
const nextButton = document.getElementById('nextButton');
const dots = document.querySelectorAll('.dot');
const quoteForm = document.getElementById('quoteForm');
let currentIndex = 0;

function renderCarousel() {
  carousel.innerHTML = slides.map((slide) => `
    <div class="carousel-item">
      <img src="${slide.src}" alt="${slide.alt}" />
    </div>
  `).join('');
  updateCarousel();
}

function updateCarousel() {
  const offset = -currentIndex * 100;
  carousel.style.transform = `translateX(${offset}%)`;
  dots.forEach((dot, index) => {
    dot.classList.toggle('bg-[#2470c1]', index === currentIndex);
    dot.classList.toggle('bg-[#2470c1]/20', index !== currentIndex);
  });
}

function nextSlide() {
  currentIndex = (currentIndex + 1) % slides.length;
  updateCarousel();
}

function previousSlide() {
  currentIndex = (currentIndex - 1 + slides.length) % slides.length;
  updateCarousel();
}

prevButton.addEventListener('click', previousSlide);
nextButton.addEventListener('click', nextSlide);

if (quoteForm) {
  quoteForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const data = new FormData(quoteForm);
    const message = `Olá Printer, envio orçamento detalhado:%0A
- Nome: ${encodeURIComponent(data.get('nome') || '')}%0A
- Empresa: ${encodeURIComponent(data.get('empresa') || '')}%0A
- Contato: ${encodeURIComponent(data.get('contato') || '')}%0A
- E-mail: ${encodeURIComponent(data.get('email') || '')}%0A
- Ativo/Máquina: ${encodeURIComponent(data.get('ativo') || '')}%0A
- Modelo/Código: ${encodeURIComponent(data.get('modelo') || '')}%0A
- Especificação: ${encodeURIComponent(data.get('especificacao') || '')}%0A
- Problema: ${encodeURIComponent(data.get('problema') || '')}%0A
- Prioridade: ${encodeURIComponent(data.get('prioridade') || '')}`;
    const whatsappUrl = `https://wa.me/5511999999999?text=${message}`;
    window.open(whatsappUrl, '_blank');
  });
}

renderCarousel();
setInterval(nextSlide, 6000);
