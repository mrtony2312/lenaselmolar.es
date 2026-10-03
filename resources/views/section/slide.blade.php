<section>
    <div class="hero-slider">
        <div class="slide active" style="background-image:url({{ asset('wp-content/uploads/2025/10/678998765434567806.webp') }});">
            <div class="overlay"></div>
            <div class="content">
                <p class="slide-title" style="color:white">Leña densificada</p>
                <p>El confort de los troncos comprimidos, ¡entregados en tu puerta!</p>
                <a href="{{ route('loja') }}" class=" btn-1">TIENDA</a>
            </div>
        </div>
        <div class="slide" style="background-image:url({{ asset('wp-content/uploads/2025/10/holzpellets-rekord-flamme.webp') }});">
            <div class="overlay"></div>
            <div class="content">
                <p class="slide-title" style="color:white">Pellets de madera certificados</p>
                <p>¡Somos el socio ideal!</p>
                <a href="{{ route('loja') }}" class="btn-1">TIENDA</a>
            </div>
        </div>
        <div class="slide" style="background-image:url({{ asset('wp-content/uploads/2025/10/fire-with-burning-firewood-smoldering-coals-dark_124507-21228.jpg') }});">
            <div class="overlay"></div>
            <div class="content">
                <p class="slide-title" style="color:white">Comprar leña ecológica</p>
                <p>Leña cuidadosamente seleccionada para ti</p>
                <a href="{{ route('loja') }}" class="btn-3">TIENDA</a>
            </div>
        </div>


        <!-- Flèches -->
        <div class="arrow left">&#10094;</div>
        <div class="arrow right">&#10095;</div>

        <div class="counter"><span id="current">01</span> / 03</div>
        <div class="dots" id="dots"></div>
    </div>

    <script>
        const slides = document.querySelectorAll('.slide');
        const prev = document.querySelector('.arrow.left');
        const next = document.querySelector('.arrow.right');
        const current = document.getElementById('current');
        const dotsContainer = document.getElementById('dots');
        const slider = document.querySelector('.hero-slider');

        let index = 0;
        const total = slides.length;
        let isTransitioning = false;
        let autoSlideInterval;

        let touchStartX = 0;
        let touchEndX = 0;
        let touchStartY = 0;
        let touchEndY = 0;

        const effects = ['fade', 'zoom', 'slide-left', 'slide-right', 'slide-up', 'slide-down', 'rotate3d', 'blur', 'zoom-rotate'];

        function createDots() {
            for(let i = 0; i < total; i++) {
                const dot = document.createElement('div');
                dot.className = `dot ${i === 0 ? 'active' : ''}`;
                dot.setAttribute('aria-label', `Ir a la diapositiva ${i + 1}`);
                dot.addEventListener('click', () => {
                    if(i !== index && !isTransitioning) goToSlide(i);
                });
                dotsContainer.appendChild(dot);
            }
        }

        function getRandomEffect() {
            return effects[Math.floor(Math.random() * effects.length)];
        }

        function showSlide(nextIndex) {
            if(isTransitioning || nextIndex === index) return;
            isTransitioning = true;

            const currentSlide = slides[index];
            const nextSlide = slides[nextIndex];

            // Nettoyer les anciennes classes d'effet
            slides.forEach(slide => {
                effects.forEach(eff => slide.classList.remove(eff));
            });

            const effect = getRandomEffect();
            nextSlide.classList.add(effect);

            // Forcer le reflow pour déclencher l'animation
            void nextSlide.offsetWidth;

            currentSlide.classList.remove('active');
            currentSlide.classList.add('prev-active');
            nextSlide.classList.add('active');

            index = nextIndex;
            current.textContent = String(index + 1).padStart(2, '0');
            updateDots();

            setTimeout(() => {
                currentSlide.classList.remove('prev-active');
                nextSlide.classList.remove(effect);
                isTransitioning = false;
            }, 1400);
        }

        function updateDots() {
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
                dot.setAttribute('aria-current', i === index ? 'true' : 'false');
            });
        }

        function goToSlide(i) {
            if(i >= 0 && i < total) {
                showSlide(i);
            }
        }

        function nextSlide() {
            showSlide((index + 1) % total);
        }

        function prevSlide() {
            showSlide((index - 1 + total) % total);
        }

        next.addEventListener('click', nextSlide);
        prev.addEventListener('click', prevSlide);

        function startAutoSlide() {
            clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(nextSlide, 6000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }

        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);

        // Gestion du focus pour l'accessibilité
        slider.addEventListener('focusin', stopAutoSlide);
        slider.addEventListener('focusout', startAutoSlide);

        createDots();
        startAutoSlide();

        // Swipe tactile - version corrigée
        function handleTouchStart(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }

        function handleTouchEnd(e) {
            if (isTransitioning) return;

            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;

            const diffX = touchStartX - touchEndX;
            const diffY = touchStartY - touchEndY;

            // Seulement si le mouvement est principalement horizontal
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    nextSlide(); // swipe gauche → suivant
                } else {
                    prevSlide(); // swipe droite → précédent
                }
            }
        }

        // Écouteurs tactiles
        slider.addEventListener('touchstart', handleTouchStart, { passive: true });
        slider.addEventListener('touchend', handleTouchEnd, { passive: true });

        // Navigation clavier
        document.addEventListener('keydown', (e) => {
            if (!slider.contains(document.activeElement)) return;

            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    prevSlide();
                    break;
                case 'ArrowRight':
                case ' ':
                    e.preventDefault();
                    nextSlide();
                    break;
                case 'Home':
                    e.preventDefault();
                    goToSlide(0);
                    break;
                case 'End':
                    e.preventDefault();
                    goToSlide(total - 1);
                    break;
            }
        });

        // Nettoyage
        window.addEventListener('beforeunload', () => {
            stopAutoSlide();
        });
    </script>
</section>
