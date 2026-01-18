(function () {
  function initSlider() {
    const slider = document.querySelector('[data-sc-slider="1"]');
    if (!slider) return;

    const slides = Array.from(slider.querySelectorAll('.sc-slide'));
    const dots = Array.from(slider.querySelectorAll('.sc-dot'));
    if (!slides.length) return;

    let idx = 0;
    let timer = null;

    function show(i) {
      idx = (i + slides.length) % slides.length;
      slides.forEach((s, k) => s.classList.toggle('sc-slide--active', k === idx));
      dots.forEach((d, k) => d.classList.toggle('sc-dot--active', k === idx));
    }

    dots.forEach((d) => {
      d.addEventListener('click', () => {
        const i = parseInt(d.getAttribute('data-sc-dot'), 10);
        show(i);
        restart();
      });
    });

    function restart() {
      if (timer) clearInterval(timer);
      timer = setInterval(() => show(idx + 1), 6000);
    }

    show(0);
    restart();
  }

  document.addEventListener('DOMContentLoaded', initSlider);
})();
