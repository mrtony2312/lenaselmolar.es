<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Variables globales pour gérer l'état du modal
        let currentProductData = null;
        let currentModalInstance = null;
        let currentImageSlider = null;

        // Charger Magnific Popup si nécessaire
        function loadMagnificPopup() {
            return new Promise((resolve) => {
                if (typeof $.magnificPopup !== 'undefined') {
                    resolve();
                    return;
                }

                const magnificScript = document.createElement('script');
                magnificScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js';
                magnificScript.onload = function() {
                    // Charger le CSS
                    const magnificStyle = document.createElement('link');
                    magnificStyle.rel = 'stylesheet';
                    magnificStyle.href = 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css';
                    document.head.appendChild(magnificStyle);
                    resolve();
                };
                document.head.appendChild(magnificScript);
            });
        }

        // Fonction pour tronquer la description
        function truncateDescription(description, maxLength = 400) {
            if (!description) return '';
            if (description.length <= maxLength) return description;
            return description.substring(0, maxLength) + '...';
        }

        // Nettoyer les anciens modaux
        function cleanupExistingModals() {
            // Supprimer tous les modaux existants
            document.querySelectorAll('.maia-quickview, .mfp-wrap, .mfp-bg').forEach(el => {
                el.remove();
            });

            // Réinitialiser le slider
            if (currentImageSlider) {
                currentImageSlider = null;
            }

            // Fermer toute instance Magnific Popup existante
            if (typeof $.magnificPopup !== 'undefined' && $.magnificPopup.instance) {
                $.magnificPopup.close();
            }
        }

        // Fonction pour créer le slider d'images
        function createImageSlider(modalId, productId, images) {
            if (!images || images.length <= 1) return null;

            const modal = document.getElementById(modalId);
            if (!modal) return null;

            let currentIndex = 0;

            // Fonction pour mettre à jour l'image principale
            function updateMainImage(index) {
                const mainImage = modal.querySelector(`#main-image-${productId}`);
                const dots = modal.querySelectorAll('.dot');

                if (mainImage && images[index]) {
                    mainImage.src = images[index];
                    currentIndex = index;

                    // Mettre à jour les dots actifs
                    dots.forEach((dot, i) => {
                        if (dot) {
                            dot.style.background = i === index ? '#F55F1E' : '#ddd';
                        }
                    });
                }
            }

            // Initialiser les événements
            function initEvents() {
                // Événements pour les dots
                const dots = modal.querySelectorAll('.dot');
                dots.forEach(dot => {
                    if (dot) {
                        dot.addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index'));
                            updateMainImage(index);
                        });
                    }
                });

                // Navigation au clavier
                modal.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowLeft') {
                        let newIndex = currentIndex - 1;
                        if (newIndex < 0) newIndex = images.length - 1;
                        updateMainImage(newIndex);
                        e.preventDefault();
                    } else if (e.key === 'ArrowRight') {
                        let newIndex = currentIndex + 1;
                        if (newIndex >= images.length) newIndex = 0;
                        updateMainImage(newIndex);
                        e.preventDefault();
                    }
                });
            }



            // Initialiser les événements
            setTimeout(() => {
                initEvents();

                // Ajouter les événements pour les flèches après qu'elles sont créées
                const prevArrow = modal.querySelector('.prev-arrow');
                const nextArrow = modal.querySelector('.next-arrow');

                if (prevArrow) {
                    prevArrow.addEventListener('click', function() {
                        let newIndex = currentIndex - 1;
                        if (newIndex < 0) newIndex = images.length - 1;
                        updateMainImage(newIndex);
                    });
                }

                if (nextArrow) {
                    nextArrow.addEventListener('click', function() {
                        let newIndex = currentIndex + 1;
                        if (newIndex >= images.length) newIndex = 0;
                        updateMainImage(newIndex);
                    });
                }
            }, 100);

            return {
                updateMainImage,
                currentIndex
            };
        }

        // Fonction pour afficher le modal
        function showQuickViewModal(product) {
            // Nettoyer les anciens modaux d'abord
            cleanupExistingModals();

            // Stocker les données du produit courant
            currentProductData = product;

            const truncatedDescription = truncateDescription(product.short_description);
            const modalId = `quick-view-modal-${product.id}`;
            const galleryId = `product-gallery-${product.id}`;

            // Construire le HTML du modal
            const modalHTML = `
            <div id="${modalId}" class="maia-quickview mfp-hide">
                <div class="quick-view-container" style="background: white; border-radius: 8px; box-shadow: 0 5px 30px rgba(0,0,0,0.3); max-width: 1400px; margin: 40px auto; position: relative;">
                    <div id="tbay-quick-view-body" class="woocommerce single-product">
                        <div id="tbay-quick-view-content">
                            <div id="product-${product.id}" class="product post-${product.id} type-product status-publish has-post-thumbnail ${product.category ? 'product_cat-' + product.category.toLowerCase().replace(/\s+/g, '-') : ''} instock sale taxable shipping-taxable purchasable product-type-simple">
                                <div class="woocommerce-notices-wrapper"></div>

                                <div id="sticky-menu-bar" style="position: sticky; top: 0; background: white; z-index: 100; border-bottom: 1px solid #eee; padding: 10px 0;">
                                    <div class="container">
                                        <div class="row">
                                            <div class="menu-bar-left col-lg-7">
                                                <div class="media">
                                                    <div class="media-left media-top pull-left">
                                                        <img width="50" height="50"
                                                            src="{{ asset('${product.images[0]}') }}"

                                                             class="attachment-50x50 size-50x50 wp-post-image" alt="${product.title}"
                                                             decoding="async">
                                                    </div>
                                                    <div class="media-body">
                                                        <h2 class="product_title entry-title">${product.title}</h2>
                                                        <div class="woocommerce-product-rating">
                                                            <div class="star-rating"></div>
                                                            <a href="#reviews" class="woocommerce-review-link">
                                                                <span class="count">0</span> comentarios de clientes
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="menu-bar-right product col-lg-5">
                                                <p class="price">
                                                    ${product.old_price ? `
                                                        <del aria-hidden="true">
                                                            <span class="woocommerce-Price-amount amount">
                                                                <bdi>${product.old_price}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi>
                                                            </span>
                                                        </del>
                                                        <span class="screen-reader-text">El precio original era: ${product.old_price}&nbsp;€.</span>
                                                    ` : ''}
                                                    <ins aria-hidden="true">
                                                        <span class="woocommerce-Price-amount amount">
                                                            <bdi>${product.price}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi>
                                                        </span>
                                                    </ins>
                                                    ${product.old_price ? `
                                                        <span class="screen-reader-text">El precio actual es: ${product.price}&nbsp;€.</span>
                                                    ` : ''}
                                                    <small class="woocommerce-price-suffix">IVA incluido</small>
                                                </p>
                                                <a id="sticky-custom-add-to-cart" href="javascript:void(0);" data-product-id="${product.id}">Añadir</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-main-content" style="padding: 20px;">
                                    <div class="row">
                                        <div class="image-mains col-lg-6">
                                            ${product.old_price ? `
                                                <span class="onsale"><span class="saled">Oferta</span></span>
                                            ` : ''}

                                            <div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images has-gallery"
                                                 data-columns="4" data-rtl="no" data-layout="horizontal" style="opacity: 1; transition: opacity 0.25s ease-in-out;">
                                                <div class="woocommerce-product-gallery__wrapper" id="${galleryId}">
                                                    <div class="main-image-container" style="position: relative; margin-bottom: 15px; width: 100%">
                                                        <img id="main-image-${product.id}"
                                                             src="{{ asset('${product.images[0]}') }}"
                                                             alt="${product.title}"
                                                             class="wp-post-image main-product-image"
                                                             style="width: 100%; height: 400px; object-fit: contain; background: #f5f5f5; border-radius: 8px; padding: 10px;">

                                                        ${product.images.length > 1 ? `
                                                            <div class="slider-dots" style="display: flex; justify-content: center; gap: 8px; margin-top: 15px;">
                                                                ${product.images.map((image, index) => `
                                                                    <button class="dot" data-index="${index}"
                                                                            style="width: 12px; height: 12px; border-radius: 50%; border: none; background: ${index === 0 ? '#F55F1E' : '#ddd'}; cursor: pointer;"></button>
                                                                `).join('')}
                                                            </div>
                                                        ` : ''}
                                                    </div>
                                                </div>

                                                <div class="details-btn-wrapper">
                                                    <a class="view-details-btn" href="/producto/${product.slug}">Ver detalles</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="information col-lg-6">
                                            <div class="summary entry-summary">
                                                <div class="top-single-product">
                                                    <p class="price">
                                                        ${product.old_price ? `
                                                            <del aria-hidden="true">
                                                                <span class="woocommerce-Price-amount amount">
                                                                    <bdi>${product.old_price}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi>
                                                                </span>
                                                            </del>
                                                            <span class="screen-reader-text">El precio original era: ${product.old_price}&nbsp;€.</span>
                                                        ` : ''}
                                                        <ins aria-hidden="true">
                                                            <span class="woocommerce-Price-amount amount">
                                                                <bdi>${product.price}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi>
                                                            </span>
                                                        </ins>
                                                        ${product.old_price ? `
                                                            <span class="screen-reader-text">El precio actual es: ${product.price}&nbsp;€.</span>
                                                        ` : ''}
                                                        <small class="woocommerce-price-suffix">IVA incluido</small>
                                                    </p>
                                                    <h2 class="product_title entry-title">${product.title}</h2>
                                                    <div class="woocommerce-product-rating">
                                                        <div class="star-rating"></div>
                                                        <a href="#reviews" class="woocommerce-review-link">
                                                            <span class="count">0</span> comentarios de clientes
                                                        </a>
                                                    </div>
                                                </div>

                                                ${truncatedDescription ? `
                                                    <div class="woocommerce-product-details__short-description">
                                                        <p>${truncatedDescription}</p>
                                                        ${product.description && product.description.length > 400 ? `
                                                            <a href="/products/${product.slug}" class="read-more-link" style="color: #F55F1E; font-weight: 500; text-decoration: none;">
                                                                Leer más...
                                                            </a>
                                                        ` : ''}
                                                    </div>
                                                ` : ''}

                                                <form class="cart" action="#" method="post" enctype="multipart/form-data">
                                                    <div class="mobile-infor-wrapper d-none">
                                                        <div class="d-flex">
                                                            <div class="me-3">
                                                                <img width="150"  src="{{ asset('${product.images[0]}') }}"
                                                                     class="attachment-woocommerce_gallery_thumbnail size-woocommerce_gallery_thumbnail"
                                                                     alt="${product.title}" decoding="async">
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="infor-body">
                                                                    <p class="price">
                                                                        ${product.old_price ? `
                                                                            <del aria-hidden="true">
                                                                                <span class="woocommerce-Price-amount amount">
                                                                                    <bdi>${product.old_price}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi>
                                                                                </span>
                                                                            </del>
                                                                            <span class="screen-reader-text">El precio original era: ${product.old_price}&nbsp;€.</span>
                                                                        ` : ''}
                                                                        <ins aria-hidden="true">
                                                                            <span class="woocommerce-Price-amount amount">
                                                                                <bdi>${product.price}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi>
                                                                            </span>
                                                                        </ins>
                                                                        ${product.old_price ? `
                                                                            <span class="screen-reader-text">El precio actual es: ${product.price}&nbsp;€.</span>
                                                                        ` : ''}
                                                                        <small class="woocommerce-price-suffix">IVA incluido</small>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="shop-now" class="shop-now has-buy-now has-wishlist">
                                                        <div class="quantity">
                                                           <div class="quantity-selector">
                                                                    <button type="button" class="quantity-m">−
                                                                    </button>
                                                                    <input type="number" class="quantity-add"
                                                                           value="1"
                                                                           aria-label="Cantidad del producto">
                                                                    <button type="button" class="quantity-p">＋
                                                                    </button>
                                                                </div>
                                                        </div>

                                                        <a href="javascript:void(0);"
                                                         data-product-id="${product.id}"
                                                                class="single_add_to_cart_button button alt add-to-cart-quickview ajax_add_to_cart"
                                                                 aria-label="Añadir al carrito: &ldquo;${product.title}&rdquo;">
                                                            Añadir
                                                        </a>

                                                        <a class="tbay-buy-now button buy-now-quickview" href="{{ route('carrinho') }}">
                                                            Comprar ahora
                                                        </a>
                                                        <input type="hidden" value="0" name="maia_buy_now">

                                                        <div class="maia-custom-fields d-none">
                                                            <input type="hidden" name="maia-enable-addtocart-ajax" value="0">
                                                            <input type="hidden" name="data-product_id" value="${product.id}">
                                                            <input type="hidden" name="data-type" value="simple">
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="product_meta">
                                                    ${product.ref ? `
                                                        <span class="sku_wrapper">REF: <span class="sku">${product.ref}</span></span>
                                                    ` : ''}
                                                    ${product.category ? `
                                                        <span class="posted_in">Categoría:
                                                            <a href="#" rel="tag">${product.category.toUpperCase()}</a>
                                                        </span>
                                                    ` : ''}
                                                </div>

                                                <div data-elementor-type="wp-post" data-elementor-id="3068" class="elementor elementor-3068">
                                                    <section class="elementor-section elementor-top-section elementor-element elementor-element-29ff4f4 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="29ff4f4" data-element_type="section">
                                                        <div class="elementor-container elementor-column-gap-no">
                                                            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-eb17651" data-id="eb17651" data-element_type="column">
                                                                <div class="elementor-widget-wrap elementor-element-populated">
                                                                    <div class="elementor-element elementor-element-7114f96 elementor-align-center elementor-widget elementor-widget-button" data-id="7114f96" data-element_type="widget" data-widget_type="button.default">
                                                                        <a class="elementor-button elementor-size-sm" role="button">
                                                                            <span class="elementor-button-content-wrapper">
                                                                                <span class="elementor-button-text">Pago por transferencia bancaria</span>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                    <section class="elementor-section elementor-inner-section elementor-element elementor-element-48884e7 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="48884e7" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                                                        <div class="elementor-container elementor-column-gap-no">
                                                                            <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-3e43a0e" data-id="3e43a0e" data-element_type="column">
                                                                                <div class="elementor-widget-wrap elementor-element-populated">
                                                                                    <div class="elementor-element elementor-element-b333598 elementor-widget elementor-widget-icon-box" data-id="b333598" data-element_type="widget" data-widget_type="icon-box.default">
                                                                                        <div class="elementor-icon-box-wrapper">
                                                                                            <div class="elementor-icon-box-content">
                                                                                                <h3 class="elementor-icon-box-title">
                                                                                                    <span>Envío</span>
                                                                                                </h3>
                                                                                                <p class="elementor-icon-box-description">
                                                                                                    🚚 {{ \App\Support\ShippingPolicy::shortLabel() }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-73ccedf" data-id="73ccedf" data-element_type="column">
                                                                                <div class="elementor-widget-wrap elementor-element-populated">
                                                                                    <div class="elementor-element elementor-element-9f71d2b elementor-widget elementor-widget-icon-box" data-id="9f71d2b" data-element_type="widget" data-widget_type="icon-box.default">
                                                                                        <div class="elementor-icon-box-wrapper">
                                                                                            <div class="elementor-icon-box-content">
                                                                                                <h3 class="elementor-icon-box-title">
                                                                                                    <span>100%</span>
                                                                                                </h3>
                                                                                                <p class="elementor-icon-box-description">
                                                                                                    🔒 Pago 100% seguro
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-6b1e690" data-id="6b1e690" data-element_type="column">
                                                                                <div class="elementor-widget-wrap elementor-element-populated">
                                                                                    <div class="elementor-element elementor-element-4aed4d7 elementor-widget elementor-widget-icon-box" data-id="4aed4d7" data-element_type="widget" data-widget_type="icon-box.default">
                                                                                        <div class="elementor-icon-box-wrapper">
                                                                                            <div class="elementor-icon-box-content">
                                                                                                <h3 class="elementor-icon-box-title">
                                                                                                    <span>Pedido seguro</span>
                                                                                                </h3>
                                                                                                <p class="elementor-icon-box-description">
                                                                                                    📦 Producto en stock
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button title="Close (Esc)" type="button" class="mfp-close" style="position: absolute; top: 20px; right: 20px; z-index: 1000; background: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                        <i class="tb-icon tb-icon-close-01"></i>
                    </button>
                </div>
            </div>
        `;

            // Ajouter le modal au body
            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // Initialiser le slider d'images après que le modal est ajouté au DOM
            setTimeout(() => {
                if (product.images && product.images.length > 1) {
                    currentImageSlider = createImageSlider(modalId, product.id, product.images);
                }
            }, 100);

            // Initialiser les événements du modal
            initQuickViewEvents(modalId, product.id);

            // Ouvrir le modal avec Magnific Popup
            if (typeof $.magnificPopup !== 'undefined') {
                currentModalInstance = $.magnificPopup.open({
                    items: {
                        src: `#${modalId}`,
                        type: 'inline'
                    },
                    mainClass: 'mfp-move-from-top maia-quickview',
                    removalDelay: 300,
                    closeOnContentClick: false,
                    closeOnBgClick: true,
                    closeBtnInside: true,
                    fixedContentPos: true,
                    fixedBgPos: true,
                    alignTop: false,
                    midClick: true,
                    callbacks: {
                        beforeOpen: function() {
                            this.st.mainClass = 'mfp-move-from-top';
                        },

                        close: function() {
                            // Nettoyer lors de la fermeture
                            cleanupExistingModals();
                            currentProductData = null;
                            currentModalInstance = null;
                            currentImageSlider = null;
                        }
                    }
                });
            }
        }

        // Initialiser les événements dans le modal
        function initQuickViewEvents(modalId, productId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            // Gérer les quantités
            const minusBtns = modal.querySelectorAll('.quantity-m');
            const plusBtns = modal.querySelectorAll('.quantity-p');
            const quantityInputs = modal.querySelectorAll('.quantity-add');

            minusBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.nextElementSibling;
                    if (input && input.classList.contains('quantity-add')) {
                        let value = parseInt(input.value) || 1;
                        if (value > 1) {
                            input.value = value - 1;
                        }
                    }
                });
            });

            plusBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.previousElementSibling;
                    if (input && input.classList.contains('quantity-add')) {
                        let value = parseInt(input.value) || 1;
                        input.value = value + 1;
                    }
                });
            });

            // Validation de l'input de quantité
            quantityInputs.forEach(input => {
                input.addEventListener('input', function() {
                    let value = parseInt(this.value) || 1;
                    if (value < 1) {
                        this.value = 1;
                    }
                });
            });

            // Bouton "Adicionar" dans le modal
            const addToCartBtns = modal.querySelectorAll('.add-to-cart-quickview');
            addToCartBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const quantity = modal.querySelector('.quantity-add')?.value || 1;
                    // Vous pouvez ajouter votre logique AJAX ici
                });
            });

            // Bouton "Adicionar" dans la sticky bar
            const stickyAddBtn = modal.querySelector('#sticky-custom-add-to-cart');
            if (stickyAddBtn) {
                stickyAddBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const quantity = modal.querySelector('.quantity-add')?.value || 1;
                    // Vous pouvez ajouter votre logique AJAX ici
                });
            }

            // Bouton de fermeture
            const closeBtn = modal.querySelector('.mfp-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (typeof $.magnificPopup !== 'undefined') {
                        $.magnificPopup.close();
                    } else {
                        cleanupExistingModals();
                    }
                });
            }
        }

        // Charger Magnific Popup
        loadMagnificPopup().then(() => {
            // Gestion des clics sur les boutons de visualisation rapide
            document.querySelectorAll('.qview-button').forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');

                    if (productId) {


                        // Charger les données du produit via AJAX
                        try {
                            const response = await fetch(`{{ route('product.quickview', ['id' => ':id']) }}`.replace(':id', productId));
                            const data = await response.json();

                            if (data.success && data.product) {
                                showQuickViewModal(data.product);
                            } else {
                                alert('Error al cargar el producto');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Erro de conexão');
                        }
                    }
                });
            });
        });
    });
</script>


<style
        id="elementor-post-3068">.elementor-3068 .elementor-element.elementor-element-29ff4f4 {
        border-style: solid;
        border-width: 1px 1px 1px 1px;
        border-color: #F55F1E;
        margin-top: 37px;
        margin-bottom: 0px;
    }

    .elementor-3068 .elementor-element.elementor-element-29ff4f4, .elementor-3068 .elementor-element.elementor-element-29ff4f4 > .elementor-background-overlay {
        border-radius: 5px 5px 5px 5px;
    }

    .elementor-3068 .elementor-element.elementor-element-7114f96 .elementor-button {
        background-color: #F55F1E;
        font-size: 16px;
        font-weight: 500;
        line-height: 24px;
        border-radius: 3px 3px 3px 3px;
        padding: 3px 8px 3px 8px;
    }

    .elementor-3068 .elementor-element.elementor-element-7114f96 {
        margin: -16px 0px calc(var(--kit-widget-spacing, 0px) + 0px) 0px;
    }

    .elementor-3068 .elementor-element.elementor-element-48884e7:not(.elementor-motion-effects-element-type-background), .elementor-3068 .elementor-element.elementor-element-48884e7 > .elementor-motion-effects-container > .elementor-motion-effects-layer {
        background-color: #9E5033;
    }

    .elementor-3068 .elementor-element.elementor-element-48884e7 {
        transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
        padding: 16px 40px 17px 40px;
    }

    .elementor-3068 .elementor-element.elementor-element-48884e7 > .elementor-background-overlay {
        transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
    }

    .elementor-3068 .elementor-element.elementor-element-3e43a0e > div.elementor-element-populated {
        padding: 0px 30px 0px 30px !important;
    }

    .elementor-3068 .elementor-element.elementor-element-b333598 .elementor-icon-box-title {
        margin-block-end: 7px;
        color: #FFFFFF;
    }

    .elementor-3068 .elementor-element.elementor-element-b333598 .elementor-icon-box-title, .elementor-3068 .elementor-element.elementor-element-b333598 .elementor-icon-box-title a {
        font-size: 24px;
        font-weight: 700;
        line-height: 36px;
    }

    .elementor-3068 .elementor-element.elementor-element-b333598 .elementor-icon-box-description {
        font-size: 16px;
        line-height: 24px;
        color: #FFFFFF;
    }

    .elementor-3068 .elementor-element.elementor-element-73ccedf > div.elementor-element-populated {
        padding: 0px 30px 0px 30px !important;
    }

    .elementor-3068 .elementor-element.elementor-element-9f71d2b .elementor-icon-box-title {
        margin-block-end: 7px;
        color: #FFFFFF;
    }

    .elementor-3068 .elementor-element.elementor-element-9f71d2b .elementor-icon-box-title, .elementor-3068 .elementor-element.elementor-element-9f71d2b .elementor-icon-box-title a {
        font-size: 24px;
        font-weight: 700;
        line-height: 36px;
    }

    .elementor-3068 .elementor-element.elementor-element-9f71d2b .elementor-icon-box-description {
        font-size: 16px;
        line-height: 24px;
        color: #FFFFFF;
    }

    .elementor-3068 .elementor-element.elementor-element-6b1e690 > div.elementor-element-populated {
        padding: 0px 30px 0px 30px !important;
    }

    .elementor-3068 .elementor-element.elementor-element-4aed4d7 .elementor-icon-box-title {
        margin-block-end: 7px;
        color: #FFFFFF;
    }

    .elementor-3068 .elementor-element.elementor-element-4aed4d7 .elementor-icon-box-title, .elementor-3068 .elementor-element.elementor-element-4aed4d7 .elementor-icon-box-title a {
        font-size: 24px;
        font-weight: 700;
        line-height: 36px;
    }

    .elementor-3068 .elementor-element.elementor-element-4aed4d7 .elementor-icon-box-description {
        font-size: 16px;
        line-height: 24px;
        color: #FFFFFF;
    }

    @media (max-width: 1024px) {
        .elementor-3068 .elementor-element.elementor-element-48884e7 {
            padding: 16px 0px 16px 0px;
        }

        .elementor-3068 .elementor-element.elementor-element-3e43a0e > div.elementor-element-populated {
            padding: 0px 15px 0px 15px !important;
        }

        .elementor-3068 .elementor-element.elementor-element-73ccedf > div.elementor-element-populated {
            padding: 0px 15px 0px 15px !important;
        }

        .elementor-3068 .elementor-element.elementor-element-6b1e690 > div.elementor-element-populated {
            padding: 0px 15px 0px 15px !important;
        }
    }

    @media (max-width: 767px) {
        .elementor-3068 .elementor-element.elementor-element-3e43a0e > div.elementor-element-populated {
            padding: 15px 15px 15px 15px !important;
        }

        .elementor-3068 .elementor-element.elementor-element-b333598 .elementor-icon-box-title {
            margin-block-end: 0px;
        }

        .elementor-3068 .elementor-element.elementor-element-b333598 .elementor-icon-box-title, .elementor-3068 .elementor-element.elementor-element-b333598 .elementor-icon-box-title a {
            font-size: 20px;
            line-height: 32px;
        }

        .elementor-3068 .elementor-element.elementor-element-73ccedf > .elementor-element-populated {
            margin: 0px 0px 15px 0px;
            --e-column-margin-right: 0px;
            --e-column-margin-left: 0px;
        }

        .elementor-3068 .elementor-element.elementor-element-9f71d2b .elementor-icon-box-title {
            margin-block-end: 0px;
        }

        .elementor-3068 .elementor-element.elementor-element-9f71d2b .elementor-icon-box-title, .elementor-3068 .elementor-element.elementor-element-9f71d2b .elementor-icon-box-title a {
            font-size: 20px;
            line-height: 32px;
        }

        .elementor-3068 .elementor-element.elementor-element-6b1e690 > div.elementor-element-populated {
            padding: 0px 15px 15px 15px !important;
        }

        .elementor-3068 .elementor-element.elementor-element-4aed4d7 .elementor-icon-box-title {
            margin-block-end: 0px;
        }

        .elementor-3068 .elementor-element.elementor-element-4aed4d7 .elementor-icon-box-title, .elementor-3068 .elementor-element.elementor-element-4aed4d7 .elementor-icon-box-title a {
            font-size: 20px;
        }
    }</style>