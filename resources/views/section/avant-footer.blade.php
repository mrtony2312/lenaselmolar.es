<section
    class="elementor-section elementor-top-section elementor-element elementor-element-cc1c659 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
    data-id="cc1c659" data-element_type="section"
    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
    <div class="elementor-container elementor-column-gap-default">
        <div
            class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-f4264d2"
            data-id="f4264d2" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div
                    class="elementor-element elementor-element-7c68128 elementor-widget elementor-widget-tbay-heading"
                    data-id="7c68128" data-element_type="widget"
                    data-widget_type="tbay-heading.default">
                    <div class="elementor-widget-container">

                        <div class="tbay-element tbay-element-heading">
                            <h3 class="heading-tbay-title style-1">
                                <span class="subtitle">Leñas El Molar</span>
                                <span class="title">Leña y carbón en El Molar</span>
                                <i aria-hidden="true" class="tb-icon tb-icon-armorial"></i>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="elementor-element elementor-widget elementor-widget-text-editor"
                    data-element_type="widget" data-widget_type="text-editor.default">
                    <div class="elementor-widget-container" style="max-width:820px;margin:0 auto;text-align:center;padding:0 16px 24px;">
                        <p>
                            <strong>Leñas El Molar C.B.</strong> (Leñas y Carbones El Molar) ofrece venta de leña de calefacción,
                            leña para estufas y chimeneas, leña para barbacoa, venta al por mayor, distribución de leña y venta de carbón.
                            Actividad indicada desde 2010. Domicilio: Calle de la Salud, 4, 28710 El Molar (Madrid).
                        </p>
                        <p>
                            Para cobertura de entrega, formatos o presupuestos, usa el
                            <a href="{{ route('contacto') }}">formulario de contacto</a>
                            o llama al +34 679 24 55 97.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>

    .testimonials-swiper-container {
        padding: 8px 4px 4px;
    }

    .testimonials-swiper {
        width: 100%;
        height: auto;
        overflow: visible;
    }

    .testimonials-swiper .swiper-slide {
        height: auto;
    }

    .testimonials-body {
        position: relative;
        height: 100%;
        background: var(--lv-surface, #fff);
        border: 1px solid var(--lv-border, #e7e1db);
        border-radius: 16px;
        padding: 36px 30px 28px;
        box-shadow: var(--lv-shadow, 0 4px 16px rgba(32, 26, 23, 0.06));
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
    }

    .testimonials-swiper .swiper-slide:hover .testimonials-body {
        transform: translateY(-6px);
        box-shadow: var(--lv-shadow-lg, 0 12px 32px rgba(32, 26, 23, 0.12));
        border-color: transparent;
    }

    .testimonial-quote-mark {
        position: absolute;
        top: 6px;
        right: 20px;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 84px;
        line-height: 1;
        color: var(--lv-primary, #c2542a);
        opacity: 0.1;
        pointer-events: none;
        user-select: none;
    }

    .testimonial-meta {
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .testimonial-rating {
        color: var(--lv-primary, #c2542a);
        font-size: 15px;
        letter-spacing: 3px;
        margin-bottom: 14px;
    }

    .excerpt {
        color: var(--lv-ink-soft, #5b5450);
        font-size: 15.5px;
        line-height: 1.7;
    }

    .testimonials-info {
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid var(--lv-border, #e7e1db);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .testimonial-avatar {
        flex: 0 0 auto;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--lv-primary, #c2542a), var(--lv-primary-dark, #9c4220));
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.5px;
    }

    .testimonials-info-right {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .name {
        font-weight: 700;
        color: var(--lv-ink, #201a17);
        font-size: 15.5px;
        line-height: 1.3;
    }

    .testimonial-verified {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: var(--lv-ink-soft, #5b5450);
        font-size: 12.5px;
    }

    .testimonial-verified i {
        color: var(--lv-success-ink, #1e7a34);
        font-size: 13px;
    }

    /* Navigation Swiper */
    .testimonials-next,
    .testimonials-prev {
        background: var(--lv-surface, #fff);
        width: 48px !important;
        height: 48px !important;
        border-radius: 50%;
        transition: all 0.25s ease;
        border: 1px solid var(--lv-border, #e7e1db);
        box-shadow: var(--lv-shadow, 0 4px 16px rgba(32, 26, 23, 0.06));
        top: 50% !important;
        transform: translateY(-50%);
        opacity: 0.95 !important;
    }

    .testimonials-next:after,
    .testimonials-prev:after {
        font-size: 16px !important;
        color: var(--lv-ink, #201a17);
        font-weight: bold;
    }

    .testimonials-next:hover,
    .testimonials-prev:hover {
        background: var(--lv-primary, #c2542a);
        border-color: var(--lv-primary, #c2542a);
        transform: translateY(-50%) scale(1.08);
        opacity: 1 !important;
    }

    .testimonials-next:hover:after,
    .testimonials-prev:hover:after {
        color: #fff;
    }

    .testimonials-prev {
        left: -6px;
    }

    .testimonials-next {
        right: -6px;
    }

    /* Pagination */
    .testimonials-pagination {
        position: relative;
        margin-top: 34px;
        bottom: 0 !important;
    }

    .testimonials-pagination .swiper-pagination-bullet {
        width: 9px;
        height: 9px;
        background: var(--lv-border, #e7e1db);
        opacity: 1;
        transition: all 0.3s ease;
    }

    .testimonials-pagination .swiper-pagination-bullet-active {
        background: var(--lv-primary, #c2542a);
        width: 28px;
        border-radius: 10px;
    }

    /* Style responsive */
    @media (max-width: 1024px) {

        .testimonials-prev {
            left: 5px;
        }

        .testimonials-next {
            right: 5px;
        }
    }

    @media (max-width: 768px) {
        /* Sur mobile, on cache complètement les boutons de navigation */
        .testimonials-next,
        .testimonials-prev {
            display: none !important;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
        }



        .testimonials-swiper .swiper-slide {
            margin: 0 10px;
        }

        .testimonials-body {
            padding: 28px 22px 24px;
        }

        .testimonials-swiper {
            padding: 0 5px;
        }

        .testimonials-pagination {
            margin-top: 25px;
        }

        /* Effet tactile pour mobile */
        .testimonials-swiper .swiper-slide {
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .testimonials-swiper .swiper-slide:active {
            cursor: grabbing;
        }

        /* Ajustement pour le swipe tactile */
        .testimonials-swiper {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }
    }

    @media (min-width: 769px) {
        /* Sur desktop/tablette, les boutons sont visibles */
        .testimonials-next,
        .testimonials-prev {
            display: flex !important;
            visibility: visible;
            opacity: 0.9;
            pointer-events: auto;
        }


    }

    @media (max-width: 480px) {
        .testimonials-swiper .swiper-slide {
            margin: 0 5px;
        }

        .testimonials-body {
            padding: 24px 18px 20px;
        }

        .testimonial-quote-mark {
            font-size: 64px;
        }

        .excerpt {
            font-size: 14px;
            line-height: 1.6;
        }

        .name {
            font-size: 15px;
        }

        .testimonials-pagination {
            margin-top: 20px;
        }

        /* Ajustement de la pagination sur mobile */
        .testimonials-pagination .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
        }

        .testimonials-pagination .swiper-pagination-bullet-active {
            width: 25px;
        }
    }

    /* Animation pour le swipe mobile */
    @media (hover: none) and (pointer: coarse) {
        .testimonials-swiper .swiper-slide {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            touch-action: pan-y;
        }

        /* Feedback visuel pendant le swipe */
        .testimonials-swiper.swiper-free-mode .swiper-slide {
            transition-timing-function: ease-out;
        }
    }

    /* Style pour les écrans tactiles */
    @media (pointer: coarse) {
        .testimonials-swiper {
            overflow: hidden;
        }
    }
</style>

<script>
    // Initialisation de Swiper
    document.addEventListener('DOMContentLoaded', function() {
        const testimonialsSwiper = new Swiper('.testimonials-swiper', {
            // Paramètres de base
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            centeredSlides: false,
            speed: 500,

            // Auto-play optionnel
            autoplay: {
                delay: 5000,
                disableOnInteraction: true,
                pauseOnMouseEnter: true
            },

            // Navigation - sera cachée sur mobile via CSS
            navigation: {
                nextEl: '.testimonials-next',
                prevEl: '.testimonials-prev',
                disabledClass: 'swiper-button-disabled',
                hiddenClass: 'swiper-button-hidden'
            },

            // Pagination
            pagination: {
                el: '.testimonials-pagination',
                clickable: true,
                dynamicBullets: true,
                dynamicMainBullets: 3,
            },

            // Configuration responsive
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 15,
                    centeredSlides: false,
                    navigation: {
                        enabled: false // Désactive la navigation sur mobile
                    }
                },
                480: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    centeredSlides: false,
                    navigation: {
                        enabled: false
                    }
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 25,
                    centeredSlides: false,
                    navigation: {
                        enabled: false
                    }
                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                    centeredSlides: false,
                    navigation: {
                        enabled: true // Active la navigation sur desktop
                    }
                },
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    centeredSlides: false,
                    navigation: {
                        enabled: true
                    }
                }
            },

            // Événements tactiles
            touchEventsTarget: 'container',
            touchRatio: 1,
            touchAngle: 45,
            grabCursor: true,
            followFinger: true,
            shortSwipes: true,
            longSwipes: true,
            longSwipesRatio: 0.5,
            longSwipesMs: 300,

            // Effets
            effect: 'slide',
            slideToClickedSlide: false,

            // Resistance aux bords
            resistance: true,
            resistanceRatio: 0.85,

            // Événements
            on: {
                init: function() {


                    // Cache les boutons sur mobile au chargement
                    const isMobile = window.innerWidth <= 768;
                    if (isMobile) {
                        const nextBtn = document.querySelector('.testimonials-next');
                        const prevBtn = document.querySelector('.testimonials-prev');
                        if (nextBtn) nextBtn.style.display = 'none';
                        if (prevBtn) prevBtn.style.display = 'none';
                    }
                },
              

                resize: function() {
                    // Gère le redimensionnement de l'écran
                    const isMobile = window.innerWidth <= 768;
                    const nextBtn = document.querySelector('.testimonials-next');
                    const prevBtn = document.querySelector('.testimonials-prev');

                    if (isMobile) {
                        // Cache les boutons sur mobile
                        if (nextBtn) nextBtn.style.display = 'none';
                        if (prevBtn) prevBtn.style.display = 'none';
                    } else {
                        // Affiche les boutons sur desktop/tablette
                        if (nextBtn) nextBtn.style.display = 'flex';
                        if (prevBtn) prevBtn.style.display = 'flex';
                    }
                }
            }
        });

        // Gestion du swipe tactile sur mobile
        let touchStartX = 0;
        let touchEndX = 0;
        let isSwiping = false;

        const testimonialsContainer = document.querySelector('.testimonials-swiper');

        if (testimonialsContainer) {
            testimonialsContainer.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                isSwiping = true;

                // Ajoute une classe pendant le swipe pour le feedback visuel
                testimonialsContainer.classList.add('swiping');
            }, { passive: true });

            testimonialsContainer.addEventListener('touchmove', function(e) {
                if (!isSwiping) return;

                // Empêche le défilement vertical pendant le swipe horizontal
                const touchMoveX = e.changedTouches[0].screenX;
                const deltaX = Math.abs(touchMoveX - touchStartX);

                if (deltaX > 10) {
                    // Si le mouvement est principalement horizontal, empêche le défilement de la page
                    e.preventDefault();
                }
            }, { passive: false });

            testimonialsContainer.addEventListener('touchend', function(e) {
                if (!isSwiping) return;

                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
                isSwiping = false;

                // Retire la classe de swipe
                testimonialsContainer.classList.remove('swiping');
            }, { passive: true });
        }

        function handleSwipe() {
            const swipeThreshold = 50;
            const deltaX = touchStartX - touchEndX;

            if (Math.abs(deltaX) > swipeThreshold) {
                if (deltaX > 0) {
                    // Swipe gauche -> suivant
                    testimonialsSwiper.slideNext();
                } else {
                    // Swipe droite -> précédent
                    testimonialsSwiper.slidePrev();
                }

                // Feedback visuel
                const activeSlide = document.querySelector('.testimonials-swiper .swiper-slide-active');
                if (activeSlide) {
                    activeSlide.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        activeSlide.style.transform = 'scale(1)';
                    }, 300);
                }
            }
        }

        // Animation au chargement
        setTimeout(() => {
            const slides = document.querySelectorAll('.testimonials-swiper .swiper-slide');
            slides.forEach((slide, index) => {
                slide.style.opacity = '0';
                slide.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    slide.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    slide.style.opacity = '1';
                    slide.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }, 300);

        // Écouteur pour le redimensionnement de la fenêtre
        window.addEventListener('resize', function() {
            const isMobile = window.innerWidth <= 768;
            const nextBtn = document.querySelector('.testimonials-next');
            const prevBtn = document.querySelector('.testimonials-prev');

            if (isMobile) {
                // Cache les boutons sur mobile
                if (nextBtn) nextBtn.style.display = 'none';
                if (prevBtn) prevBtn.style.display = 'none';
            } else {
                // Affiche les boutons sur desktop/tablette
                if (nextBtn) nextBtn.style.display = 'flex';
                if (prevBtn) prevBtn.style.display = 'flex';
            }
        });
    });
</script>

<!-- Inclure Swiper JS et CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<section
    class="elementor-section elementor-top-section elementor-element elementor-element-4d02494 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
    data-id="4d02494" data-element_type="section"
    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
    <div class="elementor-container elementor-column-gap-default">
        <div
            class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-3425850"
            data-id="3425850" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div
                    class="elementor-element elementor-element-d3cbe55 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box"
                    data-id="d3cbe55" data-element_type="widget"
                    data-widget_type="icon-box.default">
                    <div class="elementor-icon-box-wrapper">

                        <div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<i aria-hidden="true" class="tb-icon tb-icon-free-delivery"></i>				</span>
                        </div>

                        <div class="elementor-icon-box-content">

                            <h3 class="elementor-icon-box-title">
						<span>
							Envío						</span>
                            </h3>

                            <p class="elementor-icon-box-description">
                                🚚 Envío gratuito: 2 a 4 días laborables </p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div
            class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-68d1071"
            data-id="68d1071" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div
                    class="elementor-element elementor-element-398be90 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box"
                    data-id="398be90" data-element_type="widget"
                    data-widget_type="icon-box.default">
                    <div class="elementor-icon-box-wrapper">

                        <div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<i aria-hidden="true" class="tb-icon tb-icon-flexible-payment"></i>				</span>
                        </div>

                        <div class="elementor-icon-box-content">

                            <h3 class="elementor-icon-box-title">
						<span>
							 100%						</span>
                            </h3>

                            <p class="elementor-icon-box-description">
                                🔒 Pago 100% seguro </p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div
            class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-bb2357d"
            data-id="bb2357d" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div
                    class="elementor-element elementor-element-9c25f83 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box"
                    data-id="9c25f83" data-element_type="widget"
                    data-widget_type="icon-box.default">
                    <div class="elementor-icon-box-wrapper">

                        <div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<i aria-hidden="true" class="tb-icon tb-icon-support-24"></i>				</span>
                        </div>

                        <div class="elementor-icon-box-content">

                            <h3 class="elementor-icon-box-title">
						<span>
							Soporte						</span>
                            </h3>

                            <p class="elementor-icon-box-description">
                                Soporte 7 días a la semana </p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
