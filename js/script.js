const fallbackSlides = [
  {
    src: 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
    alt: 'Equipe tecnica industrial em manutencao'
  },
  {
    src: 'https://images.unsplash.com/photo-1531058020387-3be344556be6?auto=format&fit=crop&w=1200&q=80',
    alt: 'Maquina industrial com painel eletronico'
  },
  {
    src: 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1200&q=80',
    alt: 'Engrenagens metalicas e manutencao'
  }
];

let slides = [...fallbackSlides];

const carousel = document.getElementById('carousel');
const prevButton = document.getElementById('prevButton');
const nextButton = document.getElementById('nextButton');
const dotsContainer = document.getElementById('carouselDots');
const quoteForm = document.getElementById('quoteForm');
let currentIndex = 0;
let autoSlideTimer = null;

function buildDots() {
  if (!dotsContainer) return;

  dotsContainer.innerHTML = slides
    .map((_, index) => {
      const initialClass = index === currentIndex ? 'bg-[#2470c1]' : 'bg-[#2470c1]/20';
      return `<span class="dot h-3 w-3 rounded-full ${initialClass}"></span>`;
    })
    .join('');
}

function updateButtonsState() {
  const disableNavigation = slides.length <= 1;

  [prevButton, nextButton].forEach((button) => {
    if (!button) return;
    button.disabled = disableNavigation;
    button.classList.toggle('opacity-50', disableNavigation);
    button.classList.toggle('cursor-not-allowed', disableNavigation);
  });
}

function updateCarousel() {
  if (!carousel || !slides.length) return;

  const offset = -currentIndex * 100;
  carousel.style.transform = `translateX(${offset}%)`;

  if (!dotsContainer) return;
  const dots = dotsContainer.querySelectorAll('.dot');
  dots.forEach((dot, index) => {
    dot.classList.toggle('bg-[#2470c1]', index === currentIndex);
    dot.classList.toggle('bg-[#2470c1]/20', index !== currentIndex);
  });
}

function renderCarousel() {
  if (!carousel) return;

  if (!slides.length) {
    carousel.innerHTML = `
      <div class="carousel-item">
        <div class="flex h-[420px] items-center justify-center bg-slate-100 text-slate-600">
          Nenhuma imagem de banner disponivel.
        </div>
      </div>
    `;
    if (dotsContainer) dotsContainer.innerHTML = '';
    updateButtonsState();
    return;
  }

  if (currentIndex >= slides.length) {
    currentIndex = 0;
  }

  carousel.innerHTML = slides
    .map((slide) => `
      <div class="carousel-item">
        <img src="${slide.src}" alt="${slide.alt}" />
      </div>
    `)
    .join('');

  buildDots();
  updateCarousel();
  updateButtonsState();
}

function nextSlide() {
  if (slides.length <= 1) return;
  currentIndex = (currentIndex + 1) % slides.length;
  updateCarousel();
}

function previousSlide() {
  if (slides.length <= 1) return;
  currentIndex = (currentIndex - 1 + slides.length) % slides.length;
  updateCarousel();
}

function resetAutoSlide() {
  if (autoSlideTimer) {
    clearInterval(autoSlideTimer);
    autoSlideTimer = null;
  }

  if (slides.length > 1) {
    autoSlideTimer = setInterval(nextSlide, 6000);
  }
}

async function loadSlidesFromServer() {
  try {
    const response = await fetch('banners.php', { cache: 'no-store' });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);

    const payload = await response.json();
    if (Array.isArray(payload.slides)) {
      slides = payload.slides;
    } else {
      slides = [...fallbackSlides];
    }
  } catch (error) {
    slides = [...fallbackSlides];
  }

  renderCarousel();
  resetAutoSlide();
}

if (prevButton) {
  prevButton.addEventListener('click', previousSlide);
}

if (nextButton) {
  nextButton.addEventListener('click', nextSlide);
}

if (quoteForm) {
  quoteForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const data = new FormData(quoteForm);
    const message = `Ola Printer, envio orcamento detalhado:%0A
- Nome: ${encodeURIComponent(data.get('nome') || '')}%0A
- Empresa: ${encodeURIComponent(data.get('empresa') || '')}%0A
- Contato: ${encodeURIComponent(data.get('contato') || '')}%0A
- E-mail: ${encodeURIComponent(data.get('email') || '')}%0A
- Ativo/Maquina: ${encodeURIComponent(data.get('ativo') || '')}%0A
- Modelo/Codigo: ${encodeURIComponent(data.get('modelo') || '')}%0A
- Especificacao: ${encodeURIComponent(data.get('especificacao') || '')}%0A
- Problema: ${encodeURIComponent(data.get('problema') || '')}%0A
- Prioridade: ${encodeURIComponent(data.get('prioridade') || '')}`;
    const whatsappUrl = `https://wa.me/5511999999999?text=${message}`;
    window.open(whatsappUrl, '_blank');
  });
}

loadSlidesFromServer();
