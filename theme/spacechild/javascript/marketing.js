(function () {
  document.documentElement.classList.add("sc-js");

  function initSlider() {
    const slider = document.querySelector('[data-sc-slider="1"]');
    if (!slider) return;

    const slides = Array.from(slider.querySelectorAll(".sc-slide"));
    const dots = Array.from(slider.querySelectorAll(".sc-dot"));
    if (!slides.length) return;

    let idx = 0;
    let timer = null;

    function show(i) {
      idx = (i + slides.length) % slides.length;
      slides.forEach((s, k) =>
        s.classList.toggle("sc-slide--active", k === idx)
      );
      dots.forEach((d, k) => d.classList.toggle("sc-dot--active", k === idx));
    }

    dots.forEach((d) => {
      d.addEventListener("click", () => {
        const i = parseInt(d.getAttribute("data-sc-dot"), 10);
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

  function initReveals() {
    const items = Array.from(
      document.querySelectorAll(
        ".sc-animate, .sc-stagger > *, .cs-animate, .cs-animate-left, .cs-animate-right, .cs-animate-zoom, .cs-stagger > *"
      )
    );
    if (!items.length) return;

    if (!("IntersectionObserver" in window)) {
      items.forEach((item) => item.classList.add("is-visible"));
      return;
    }

    const observer = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add("is-visible");
          obs.unobserve(entry.target);
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -10% 0px" }
    );

    items.forEach((item) => observer.observe(item));
  }

  function initCarousel() {
    const carousels = Array.from(document.querySelectorAll(".cs-carousel"));
    if (!carousels.length) return;

    const prefersReduced =
      window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    carousels.forEach((carousel) => {
      const track = carousel.querySelector(".cs-carousel__track");
      const slides = Array.from(
        carousel.querySelectorAll(".cs-carousel__slide")
      );
      const dots = Array.from(
        carousel.querySelectorAll(".cs-carousel__dots span")
      );
      if (!track || slides.length === 0) return;

      let index = 0;
      let timer = null;
      const interval = parseInt(
        carousel.getAttribute("data-interval") || "5500",
        10
      );

      const setIndex = (nextIndex) => {
        index = (nextIndex + slides.length) % slides.length;
        track.style.transform = `translateX(-${index * 100}%)`;
        if (dots.length) {
          dots.forEach((dot, idx) =>
            dot.classList.toggle("is-active", idx === index)
          );
        }
      };

      const start = () => {
        if (prefersReduced) return;
        if (timer) clearInterval(timer);
        timer = setInterval(() => setIndex(index + 1), interval);
      };

      const stop = () => {
        if (timer) clearInterval(timer);
        timer = null;
      };

      setIndex(0);
      start();

      if (dots.length) {
        dots.forEach((dot, idx) => {
          dot.addEventListener("click", () => {
            setIndex(idx);
            start();
          });
        });
      }

      carousel.addEventListener("mouseenter", stop);
      carousel.addEventListener("mouseleave", start);
      carousel.addEventListener("focusin", stop);
      carousel.addEventListener("focusout", start);

      document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
          stop();
        } else {
          start();
        }
      });
    });
  }

  function initCountups() {
    const counters = Array.from(document.querySelectorAll("[data-countup]"));
    if (!counters.length) return;

    const hasIntl = typeof Intl !== "undefined" && Intl.NumberFormat;
    const formatNumber = (value, decimals) => {
      if (hasIntl) {
        return new Intl.NumberFormat("fr-FR", {
          minimumFractionDigits: decimals,
          maximumFractionDigits: decimals,
        }).format(value);
      }

      return decimals ? value.toFixed(decimals) : String(Math.round(value));
    };

    const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

    const animate = (el) => {
      if (el.dataset.counted === "1") return;
      el.dataset.counted = "1";
      el.classList.add("is-counting");

      const target = parseFloat(el.getAttribute("data-target") || "0");
      const decimals = parseInt(el.getAttribute("data-decimals") || "0", 10);
      const duration = parseInt(el.getAttribute("data-duration") || "2200", 10);
      const prefix = el.getAttribute("data-prefix") || "";
      const suffix = el.getAttribute("data-suffix") || "";
      const startTime = (window.performance && performance.now)
        ? performance.now()
        : Date.now();

      function tick(now) {
        const currentTime = (window.performance && performance.now)
          ? now
          : Date.now();
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = easeOutCubic(progress);
        const value = target * eased;
        const display = decimals
          ? parseFloat(value.toFixed(decimals))
          : Math.round(value);

        el.textContent = prefix + formatNumber(display, decimals) + suffix;

        if (progress < 1) {
          requestAnimationFrame(tick);
        } else {
          el.classList.remove("is-counting");
        }
      }

      requestAnimationFrame(tick);
    };

    if (!("IntersectionObserver" in window)) {
      counters.forEach(animate);
      return;
    }

    const observer = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          animate(entry.target);
          obs.unobserve(entry.target);
        });
      },
      { threshold: 0.4 }
    );

    counters.forEach((el) => observer.observe(el));
  }

  function initSignupModal() {
    const modal = document.querySelector('[data-sc-modal="signup"]');
    if (!modal) return;

    const iframe = modal.querySelector("iframe");
    const closeTargets = Array.from(
      modal.querySelectorAll("[data-sc-modal-close]")
    );
    const triggers = Array.from(
      document.querySelectorAll(
        'a[href*="/login/signup.php"], a[href*="/local/spacechildpages/enrol_request.php"], [data-signup-modal]'
      )
    );
    if (!triggers.length) return;

    let lastActive = null;

    const open = (url) => {
      lastActive = document.activeElement;
      modal.classList.add("is-open");
      modal.setAttribute("aria-hidden", "false");
      if (document.body) {
        document.body.classList.add("sc-modal-open");
      }

      if (iframe) {
        const src = url || iframe.getAttribute("data-src") || "";
        if (src && iframe.getAttribute("src") !== src) {
          iframe.setAttribute("src", src);
        }
      }

      const closeBtn = modal.querySelector(".sc-modal__close");
      if (closeBtn && typeof closeBtn.focus === "function") {
        closeBtn.focus();
      }
    };

    const close = () => {
      modal.classList.remove("is-open");
      modal.setAttribute("aria-hidden", "true");
      if (document.body) {
        document.body.classList.remove("sc-modal-open");
      }
      if (lastActive && typeof lastActive.focus === "function") {
        lastActive.focus();
      }
    };

    triggers.forEach((link) => {
      link.addEventListener("click", (event) => {
        if (event.defaultPrevented) return;
        if (event.button && event.button !== 0) return;
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
          return;
        }

        const href = link.getAttribute("href");
        if (!href) return;
        event.preventDefault();
        open(href);
      });
    });

    closeTargets.forEach((node) => {
      node.addEventListener("click", (event) => {
        event.preventDefault();
        close();
      });
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && modal.classList.contains("is-open")) {
        close();
      }
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    initSlider();
    initReveals();
    initCarousel();
    initCountups();
    initSignupModal();
  });
})();
