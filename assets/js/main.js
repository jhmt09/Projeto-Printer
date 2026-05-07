(function () {
  function initCarousel() {
    var root = document.querySelector('[data-carousel]');
    if (!root) {
      return;
    }

    var slides = Array.prototype.slice.call(root.querySelectorAll('[data-carousel-slide]'));
    if (!slides.length) {
      return;
    }

    var dots = Array.prototype.slice.call(root.querySelectorAll('[data-carousel-dot]'));
    var prevButton = root.querySelector('[data-carousel-prev]');
    var nextButton = root.querySelector('[data-carousel-next]');
    var current = slides.findIndex(function (slide) {
      return slide.classList.contains('is-active');
    });

    if (current < 0) {
      current = 0;
    }

    function activate(index) {
      if (!slides.length) {
        return;
      }

      current = (index + slides.length) % slides.length;

      slides.forEach(function (slide, i) {
        slide.classList.toggle('is-active', i === current);
      });

      dots.forEach(function (dot, i) {
        dot.classList.toggle('is-active', i === current);
      });
    }

    function nextSlide() {
      activate(current + 1);
    }

    function prevSlide() {
      activate(current - 1);
    }

    if (prevButton) {
      prevButton.addEventListener('click', prevSlide);
    }

    if (nextButton) {
      nextButton.addEventListener('click', nextSlide);
    }

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        var idx = Number(dot.getAttribute('data-carousel-dot'));
        if (!Number.isNaN(idx)) {
          activate(idx);
        }
      });
    });

    var timer = null;
    var interval = Number(root.getAttribute('data-interval')) || 5500;

    function startAuto() {
      if (slides.length <= 1) {
        return;
      }

      stopAuto();
      timer = window.setInterval(nextSlide, interval);
    }

    function stopAuto() {
      if (timer) {
        window.clearInterval(timer);
        timer = null;
      }
    }

    root.addEventListener('mouseenter', stopAuto);
    root.addEventListener('mouseleave', startAuto);
    root.addEventListener('focusin', stopAuto);
    root.addEventListener('focusout', startAuto);

    document.addEventListener('keydown', function (event) {
      if (event.key === 'ArrowLeft') {
        prevSlide();
      }
      if (event.key === 'ArrowRight') {
        nextSlide();
      }
    });

    activate(current);
    startAuto();
  }

  function initOrcamentoForm() {
    var form = document.getElementById('formOrcamento');
    if (!form) {
      return;
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      var numero = String(form.getAttribute('data-whatsapp-number') || '').replace(/\D+/g, '');
      if (!numero) {
        return;
      }

      var nome = String((document.getElementById('orcamentoNome') || {}).value || '').trim();
      var email = String((document.getElementById('orcamentoEmail') || {}).value || '').trim();
      var telefone = String((document.getElementById('orcamentoTelefone') || {}).value || '').trim();
      var mensagem = String((document.getElementById('orcamentoMensagem') || {}).value || '').trim();

      if (!nome || !email || !telefone || !mensagem) {
        return;
      }

      var linhas = [
        'Ola, gostaria de um orcamento.',
        'Nome: ' + nome,
        'E-mail: ' + email,
        'Telefone: ' + telefone,
        'Mensagem: ' + mensagem
      ];

      var texto = linhas.join('\n');
      var url = 'https://wa.me/' + numero + '?text=' + encodeURIComponent(texto);
      window.open(url, '_blank', 'noopener');
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initCarousel();
    initOrcamentoForm();
  });
})();