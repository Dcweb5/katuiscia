/* ============================================
   KATUISCIA — Main JavaScript (Vite + Modern)
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initMobileMenu();
  initCookiePopup();
  initScrollReveal();
  initHeroSlideshow();
  initShowcaseCarousel();
  initBackToTop();
  initSmoothHover();
});

/* ==== HEADER SCROLL EFFECT ==== */
function initHeader() {
  const header = document.querySelector('.header-k');
  if (!header) return;

  let lastScroll = 0;
  const scrollThreshold = 50;

  window.addEventListener('scroll', () => {
    const currentScroll = window.scrollY;

    if (currentScroll > scrollThreshold) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

    // Hide/show header on scroll direction
    if (currentScroll > lastScroll && currentScroll > 300) {
      header.classList.add('header-hidden');
    } else {
      header.classList.remove('header-hidden');
    }
    lastScroll = currentScroll;
  }, { passive: true });
}

/* ==== MOBILE MENU ==== */
function initMobileMenu() {
  const hamburger = document.querySelector('.hamburger-k');
  const mobileMenu = document.querySelector('.mobile-menu-k');

  if (!hamburger || !mobileMenu) return;

  hamburger.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.contains('open');
    hamburger.classList.toggle('active');
    mobileMenu.classList.toggle('open');
    document.body.style.overflow = isOpen ? '' : 'hidden';
  });

  // Close on link click
  mobileMenu.querySelectorAll('a[href]').forEach(link => {
    link.addEventListener('click', () => {
      hamburger.classList.remove('active');
      mobileMenu.classList.remove('open');
      document.body.style.overflow = '';
    });
  });

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
      hamburger.classList.remove('active');
      mobileMenu.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
}

/* ==== HERO SLIDESHOW ==== */
function initHeroSlideshow() {
  const slideshow = document.getElementById('hero-slideshow');
  if (!slideshow) return;

  const slides = slideshow.querySelectorAll('.hero-slide');
  const dots = slideshow.querySelectorAll('.hero-dot');
  const tagName = document.getElementById('hero-tag-name');
  const tagPrice = document.getElementById('hero-tag-price');

  if (slides.length === 0) return;

  let currentSlide = 0;
  let autoPlayTimer;

  function goToSlide(index) {
    // Animate out current slide
    slides[currentSlide].classList.remove('active');
    slides[currentSlide].classList.add('exiting');
    dots[currentSlide]?.classList.remove('active');

    setTimeout(() => {
      slides[currentSlide].classList.remove('exiting');
    }, 700);

    currentSlide = index;

    // Animate in new slide
    slides[currentSlide].classList.add('active');
    dots[currentSlide]?.classList.add('active');

    // Update product tag with fade
    const name = slides[currentSlide].dataset.name;
    const price = slides[currentSlide].dataset.price;

    if (tagName) {
      tagName.style.opacity = '0';
      tagName.style.transform = 'translateY(8px)';
      setTimeout(() => {
        tagName.textContent = name;
        tagName.style.opacity = '1';
        tagName.style.transform = 'translateY(0)';
      }, 250);
    }
    if (tagPrice) {
      tagPrice.style.opacity = '0';
      setTimeout(() => {
        tagPrice.textContent = price;
        tagPrice.style.opacity = '1';
      }, 300);
    }
  }

  function nextSlide() {
    const next = (currentSlide + 1) % slides.length;
    goToSlide(next);
  }

  // Dot click handlers
  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      if (i === currentSlide) return;
      goToSlide(i);
      resetAutoPlay();
    });
  });

  // Auto-play
  function startAutoPlay() {
    autoPlayTimer = setInterval(nextSlide, 4500);
  }

  function resetAutoPlay() {
    clearInterval(autoPlayTimer);
    startAutoPlay();
  }

  // Pause on hover
  slideshow.addEventListener('mouseenter', () => clearInterval(autoPlayTimer));
  slideshow.addEventListener('mouseleave', startAutoPlay);

  // Respect reduced motion
  if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    startAutoPlay();
  }
}

/* ==== COOKIE POPUP ==== */
function initCookiePopup() {
  const popup = document.getElementById('cookie-popup');
  if (!popup) return;

  if (localStorage.getItem('katuiscia_cookies')) return;

  setTimeout(() => {
    popup.classList.add('visible');
  }, 2000);

  popup.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
    localStorage.setItem('katuiscia_cookies', 'accepted');
    if (typeof gtag === 'function') {
      gtag('consent', 'update', {
        'ad_storage': 'granted',
        'ad_user_data': 'granted',
        'ad_personalization': 'granted',
        'analytics_storage': 'granted'
      });
    }
    popup.classList.add('hiding');
    setTimeout(() => popup.remove(), 500);
  });

  popup.querySelector('[data-cookie-decline]')?.addEventListener('click', () => {
    localStorage.setItem('katuiscia_cookies', 'declined');
    if (typeof gtag === 'function') {
      gtag('consent', 'update', {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied'
      });
    }
    popup.classList.add('hiding');
    setTimeout(() => popup.remove(), 500);
  });
}

/* ==== SCROLL REVEAL (IntersectionObserver) ==== */
function initScrollReveal() {
  const reveals = document.querySelectorAll('.reveal-k, .reveal-k-k');
  if (!reveals.length) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    reveals.forEach(el => el.classList.add('revealed'));
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -60px 0px'
  });

  reveals.forEach(el => observer.observe(el));
}

/* ==== SHOWCASE MOBILE CAROUSEL ==== */
function initShowcaseCarousel() {
  const track = document.querySelector('.showcase-track');
  const dots = document.querySelectorAll('.showcase-dot');

  if (!track || !dots.length) return;

  const mq = window.matchMedia('(max-width: 768px)');
  if (!mq.matches) return;

  const items = track.querySelectorAll('.showcase-item');

  function updateDots() {
    const scrollLeft = track.scrollLeft;
    const trackWidth = track.clientWidth;

    let activeIndex = 0;
    let minDist = Infinity;

    items.forEach((item, i) => {
      const itemCenter = item.offsetLeft + item.offsetWidth / 2;
      const dist = Math.abs(itemCenter - scrollLeft - trackWidth / 2);
      if (dist < minDist) {
        minDist = dist;
        activeIndex = i;
      }
    });

    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === activeIndex);
    });
  }

  track.addEventListener('scroll', updateDots, { passive: true });

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      const item = items[i];
      if (!item) return;
      const itemCenter = item.offsetLeft + item.offsetWidth / 2;
      track.scrollTo({
        left: itemCenter - track.clientWidth / 2,
        behavior: 'smooth'
      });
    });
  });
}

/* ==== BACK TO TOP ==== */
function initBackToTop() {
  const btn = document.createElement('button');
  btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 15-6-6-6 6"/></svg>';
  btn.classList.add('back-to-top-k');
  btn.setAttribute('aria-label', 'Retour en haut');
  document.body.appendChild(btn);

  window.addEventListener('scroll', () => {
    btn.classList.toggle('visible', window.scrollY > 400);
  }, { passive: true });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

/* ==== SMOOTH HOVER EFFECTS ==== */
function initSmoothHover() {
  // Add magnetic hover effect to buttons
  document.querySelectorAll('.btn-katuiscia, .btn-katuiscia-filled').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
      const rect = btn.getBoundingClientRect();
      const x = e.clientX - rect.left - rect.width / 2;
      const y = e.clientY - rect.top - rect.height / 2;
      btn.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px)`;
    });
    btn.addEventListener('mouseleave', () => {
      btn.style.transform = '';
    });
  });
}
