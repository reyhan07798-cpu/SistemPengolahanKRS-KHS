// Initialize AOS
AOS.init({
    duration: 800,
    once: true
});

// Cinematic Footer — parallax reveal + magnetic buttons + back-to-top
(function () {
    const wrapper = document.getElementById('cfWrapper');
    if (!wrapper) return;

    const giantText = document.getElementById('cfGiantText');
    const toTop = document.getElementById('cfToTop');

    // IntersectionObserver: kasih class .in-view saat wrapper masuk viewport
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                wrapper.classList.add('in-view');
            } else {
                wrapper.classList.remove('in-view');
            }
        });
    }, { threshold: 0.15 });
    io.observe(wrapper);

    // Parallax giant text — translate Y berdasarkan scroll progress di wrapper
    function onScroll() {
        const rect = wrapper.getBoundingClientRect();
        const vh = window.innerHeight;
        // Progress 0 (atas wrapper baru muncul) → 1 (sudah lewat)
        const raw = (vh - rect.top) / (vh + rect.height);
        const progress = Math.max(0, Math.min(1, raw));
        if (giantText) {
            // Geser dari +60px → -20px sambil scale 0.9 → 1
            const ty = 60 - (progress * 80);
            const sc = 0.92 + (progress * 0.08);
            giantText.style.transform = `translateX(-50%) translateY(${ty.toFixed(1)}px) scale(${sc.toFixed(3)})`;
        }
    }

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                onScroll();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
    onScroll();

    // Back to top
    toTop?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Magnetic buttons — semua elemen dengan class .cf-magnetic
    const magnetics = document.querySelectorAll('.cf-magnetic');
    magnetics.forEach(el => {
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            const tx = (x * 0.25).toFixed(2);
            const ty = (y * 0.25).toFixed(2);
            el.style.transform = `translate(${tx}px, ${ty}px)`;
        });
        el.addEventListener('mouseleave', () => {
            el.style.transform = '';
        });
    });
})();

// Gradient card 3D tilt effect — mengikuti kursor di card jurusan
(function () {
    const cards = document.querySelectorAll('.gc-card');
    if (!cards.length) return;

    const MAX_ROT = 5;

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            const rotX = -(y / rect.height) * MAX_ROT;
            const rotY = (x / rect.width) * MAX_ROT;
            card.style.transform = `translateY(-6px) rotateX(${rotX.toFixed(2)}deg) rotateY(${rotY.toFixed(2)}deg)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
})();

// SimpleHeader — sticky scroll + mobile drawer
(function () {
    const header = document.getElementById('shHeader');
    const toggle = document.getElementById('shToggle');
    const drawer = document.getElementById('shDrawer');
    const overlay = document.getElementById('shOverlay');
    if (!header) return;

    // Scrolled state:
    // - Top hero (scrollY < 30) → default transparent biru
    // - Section putih → .scrolled (bg putih)
    // - Saat di area footer cinematic → balik ke transparent biru
    const cfWrapperEl = document.getElementById('cfWrapper');
    function onScroll() {
        const y = window.scrollY;
        let overDark = false;

        // Cek apakah top header (0..64px) overlap dengan footer wrapper
        if (cfWrapperEl) {
            const rect = cfWrapperEl.getBoundingClientRect();
            // header tinggi 64px, jadi cek apakah top viewport (0) sampai 64px
            // bersinggungan dengan rect footer
            if (rect.top <= 64 && rect.bottom >= 0) {
                overDark = true;
            }
        }

        // Top hero (sebelum scroll 30px) juga "over-dark"
        if (y < 30) {
            overDark = true;
        }

        if (overDark) {
            header.classList.remove('scrolled');
        } else {
            header.classList.add('scrolled');
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // Drawer toggle (mobile)
    function openDrawer() {
        toggle?.classList.add('open');
        toggle?.setAttribute('aria-expanded', 'true');
        drawer?.classList.add('open');
        overlay?.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        toggle?.classList.remove('open');
        toggle?.setAttribute('aria-expanded', 'false');
        drawer?.classList.remove('open');
        overlay?.classList.remove('open');
        document.body.style.overflow = '';
    }

    toggle?.addEventListener('click', () => {
        if (drawer?.classList.contains('open')) closeDrawer();
        else openDrawer();
    });

    overlay?.addEventListener('click', closeDrawer);

    // Close drawer ketika klik link
    drawer?.querySelectorAll('[data-sh-close]').forEach(link => {
        link.addEventListener('click', closeDrawer);
    });

    // Close drawer dengan Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && drawer?.classList.contains('open')) closeDrawer();
    });
})();

// Scroll to top
const scrollTopBtn = document.getElementById('scrollTop');
window.addEventListener('scroll', () => {
    if (window.scrollY > 500) {
        scrollTopBtn.classList.add('visible');
    } else {
        scrollTopBtn.classList.remove('visible');
    }
});

scrollTopBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Smooth scroll for nav links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
});

// Hero tablet scroll animation — meniru framer-motion useScroll
// Progress berdasarkan total scroll halaman: tablet awal MIRING + KECIL,
// makin scroll ke bawah → makin TEGAK + makin BESAR
(function () {
    const tablet = document.querySelector('.hero-x-browser') || document.querySelector('.hero-x-tablet');
    if (!tablet) return;

    tablet.style.opacity = '1';
    tablet.style.transition = 'none';

    function clamp(v, min, max) { return Math.max(min, Math.min(max, v)); }

    function update() {
        // Pakai window scrollY relatif terhadap tinggi hero section
        // Progress 0→1 saat user scroll dari atas page sampai 1.5x tinggi viewport
        const scrollY = window.scrollY || window.pageYOffset || 0;
        const vh = window.innerHeight;
        const range = vh * 1.2; // jarak scroll untuk animasi penuh
        const progress = clamp(scrollY / range, 0, 1);

        const isMobile = window.innerWidth <= 768;
        const startScale = isMobile ? 0.85 : 1.05;
        const endScale = isMobile ? 1 : 1;
        const scale = startScale + (endScale - startScale) * progress;
        const rotate = 25 * (1 - progress); // 25deg → 0deg (lebih tegas)
        const translateY = 60 * (1 - progress); // 60px → 0

        tablet.style.transform =
            `rotateX(${rotate.toFixed(2)}deg) scale(${scale.toFixed(3)}) translateY(${translateY.toFixed(1)}px)`;
    }

    let ticking = false;
    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                update();
                ticking = false;
            });
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update(); // initial — set state awal (miring + kecil)
})();

// Hero pill image carousel — rotate setiap 3 detik
(function () {
    const pill = document.getElementById('heroPill');
    if (!pill) return;
    const imgs = pill.querySelectorAll('.hero-x-pill-img');
    if (imgs.length < 2) return;

    let current = 0;
    setInterval(() => {
        imgs[current].classList.remove('active');
        current = (current + 1) % imgs.length;
        imgs[current].classList.add('active');
    }, 3000);
})();

// Sparkles text — generate sparkle particles around .sparkle-text elements
(function () {
    const SPARKLE_PATH = 'M9.825.844a1.42 1.42 0 0 1 1.35 0l.687 1.876c.539 1.472.53 3.671 1.638 4.78s3.308 1.099 4.78 1.638l1.876.687a1.42 1.42 0 0 1 0 1.35l-1.876.687c-1.472.539-3.671.53-4.78 1.638s-1.099 3.308-1.638 4.78l-.687 1.876a1.42 1.42 0 0 1-1.35 0l-.687-1.876c-.539-1.472-.53-3.671-1.638-4.78s-3.308-1.099-4.78-1.638L.844 11.175a1.42 1.42 0 0 1 0-1.35l1.876-.687c1.472-.539 3.671-.53 4.78-1.638s1.099-3.308 1.638-4.78L9.825.844Z';

    function rand(min, max) { return Math.random() * (max - min) + min; }

    function makeSparkle(color, scale, delay) {
        const el = document.createElement('span');
        el.className = 'sparkle';
        el.style.left = `${rand(0, 100)}%`;
        el.style.top = `${rand(0, 100)}%`;
        el.style.setProperty('--s', scale.toFixed(2));
        el.style.animationDelay = `${delay.toFixed(2)}s`;
        el.innerHTML = `<svg viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><path d="${SPARKLE_PATH}" fill="${color}"/></svg>`;
        return el;
    }

    document.querySelectorAll('.sparkle-text').forEach(target => {
        const count = parseInt(target.dataset.sparkleCount || '8', 10);
        const styles = getComputedStyle(target);
        const c1 = styles.getPropertyValue('--sparkle-color-1').trim() || '#9E7AFF';
        const c2 = styles.getPropertyValue('--sparkle-color-2').trim() || '#FE8BBB';

        // Wrap text content in <strong> if not already
        if (!target.querySelector('strong')) {
            const inner = target.innerHTML;
            target.innerHTML = `<strong>${inner}</strong>`;
        }

        for (let i = 0; i < count; i++) {
            const color = Math.random() > 0.5 ? c1 : c2;
            const scale = rand(0.4, 1.3);
            const delay = rand(0, 2);
            target.appendChild(makeSparkle(color, scale, delay));
        }
    });
})();

// Gallery hover carousel — prev/next navigation
(function () {
    const carousel = document.getElementById('ghCarousel');
    const track = document.getElementById('ghTrack');
    const prevBtn = document.getElementById('ghPrev');
    const nextBtn = document.getElementById('ghNext');
    if (!carousel || !track || !prevBtn || !nextBtn) return;

    let currentIndex = 0;

    function getCardWidth() {
        const card = track.querySelector('.if-card') || track.querySelector('.gh-card');
        if (!card) return 0;
        // include gap (1.5rem = 24px)
        return card.offsetWidth + 24;
    }

    function getMaxIndex() {
        const cards = track.querySelectorAll('.if-card, .gh-card');
        const containerWidth = carousel.offsetWidth;
        const cardWidth = getCardWidth();
        if (cardWidth === 0) return 0;
        const visibleCards = Math.floor(containerWidth / cardWidth);
        return Math.max(0, cards.length - visibleCards);
    }

    function updateButtons() {
        const max = getMaxIndex();
        prevBtn.disabled = currentIndex <= 0;
        nextBtn.disabled = currentIndex >= max;
    }

    function update() {
        const cardWidth = getCardWidth();
        track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        updateButtons();
    }

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            update();
        }
    });

    nextBtn.addEventListener('click', () => {
        const max = getMaxIndex();
        if (currentIndex < max) {
            currentIndex++;
            update();
        }
    });

    // Reset position on window resize
    window.addEventListener('resize', () => {
        const max = getMaxIndex();
        if (currentIndex > max) currentIndex = max;
        update();
    });

    // Initial state
    setTimeout(update, 50);
})();