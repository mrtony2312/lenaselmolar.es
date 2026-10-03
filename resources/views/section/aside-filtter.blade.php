<aside id="sidebar-shop" class="sidebar  d-none d-xl-block tbay-sidebar-shop col-12 col-xl-3 ">
    <aside id="woof_widget-1" class="widget WOOF_Widget">
        <div class="widget widget-woof">

            <form id="filterForm" method="GET" action="{{ url()->current() }}">
                <input type="hidden" name="swoof" value="1">

                <div data-slug="" class="woof woof_sid woof_sid_widget " data-sid="widget"
                     data-shortcode="woof sid=&#039;widget&#039; mobile_mode=&#039;0&#039; autosubmit=&#039;-1&#039; start_filtering_btn=&#039;0&#039; price_filter=&#039;3&#039; redirect=&#039;&#039; ajax_redraw=&#039;0&#039; btn_position=&#039;b&#039; dynamic_recount=&#039;-1&#039; "
                     data-redirect="" data-autosubmit="1" data-ajax-redraw="0">


                    <!--- here is possible to drop html code which is never redraws by AJAX ---->

                    <div class="woof_redraw_zone" data-woof-ver="3.3.6.5" data-icheck-skin="none">


                        <div data-css-class="woof_price3_search_container"
                             class="woof_price3_search_container woof_container woof_price_filter  woof_fs_by_price ">
                            <div class="woof_container_overlay_item"></div>
                            <div class="woof_container_inner">
                                <h4>Precio</h4>
                                <label class="woof_wcga_label_hide" for="69492a7b6d507">Filtro de precio</label>
                                <input class="woof_range_slider" id="69492a7b6d507"
                                       data-skin="round" data-taxes="1" data-min="110"
                                       data-max="2997"
                                       data-min-now="{{ $currentFilters['min_price'] ?? 110 }}"
                                       data-max-now="{{ $currentFilters['max_price'] ?? 2997 }}"
                                       data-step="1"
                                       data-slider-prefix="" data-slider-postfix=" &euro;"
                                       name="price_range"
                                       value="{{ $currentFilters['min_price'] ?? 110 }}_{{ $currentFilters['max_price'] ?? 2997 }}"/>
                            </div>
                        </div>
                        <div data-css-class="woof_checkbox_featured_container"
                             class="woof_checkbox_featured_container woof_container woof_container_product_visibility  woof_fs_by_featured ">
                            <div class="woof_container_overlay_item"></div>
                            <div class="woof_container_inner">
                                <input type="checkbox" class="woof_checkbox_featured"
                                       id="woof_checkbox_featured" name="product_visibility"
                                       value="featured"
                                       {{ request('product_visibility') == 'featured' ? 'checked' : '' }}
                                       onchange="document.getElementById('filterForm').submit()"/>&nbsp;&nbsp;
                                <label for="woof_checkbox_featured">Productos destacados</label><br/>
                            </div>
                        </div>

                        <div data-css-class="woof_checkbox_instock_container"
                             class="woof_checkbox_instock_container woof_container woof_container_stock  woof_fs_by_instock ">
                            <div class="woof_container_overlay_item"></div>
                            <div class="woof_container_inner">
                                <input type="checkbox" class="woof_checkbox_instock"
                                       id="woof_checkbox_instock" name="stock"
                                       value="instock"
                                       {{ request('stock') == 'instock' ? 'checked' : '' }}
                                       onchange="document.getElementById('filterForm').submit()"/>&nbsp;&nbsp;
                                <label for="woof_checkbox_instock">En stock</label><br/>
                            </div>
                        </div>
                        <div data-css-class="woof_container_product_cat"
                             class="woof_container woof_container_radio woof_container_product_cat woof_container_9 woof_container_categories  woof_fs_product_cat ">
                            <div class="woof_container_overlay_item"></div>
                            <div class="woof_container_inner woof_container_inner_categories">
                                <h4>
                                    Categorías </h4>

                                <script type="text/javascript" id="woof-husky-js-extra">
                                    var woof_husky_txt = {
                                        "ajax_url": "#",
                                        "plugin_uri": "#",
                                        "loader": "/wp-content/plugins/woocommerce-products-filter/ext/by_text/assets/img/ajax-loader.gif",
                                        "not_found": "Nothing found!", "prev": "Prev", "next": "Next", "site_link":
                                            "/",
                                        "default_data": {
                                            "placeholder": "",
                                            "behavior": "title_or_content_or_excerpt",
                                            "search_by_full_word": 0,
                                            "autocomplete": 1,
                                            "how_to_open_links": 0,
                                            "taxonomy_compatibility": 0,
                                            "sku_compatibility": 1,
                                            "custom_fields": "",
                                            "search_desc_variant": 0,
                                            "view_text_length": 10,
                                            "min_symbols": 3,
                                            "max_posts": 10,
                                            "image": "",
                                            "notes_for_customer": "",
                                            "template": "default",
                                            "max_open_height": 300,
                                            "page": 0
                                        }
                                    };
                                </script>

                                <div class="woof_block_html_items">
                                    <ul class="woof_list woof_list_radio">
                                        <!-- Option "Toutes les catégories" -->
                                        <li class="woof_term_all">
                                            <input type="radio" id="woof_cat_all"
                                                   class="woof_radio_term woof_radio_term_all"
                                                   data-slug="" data-term-id=""
                                                   name="product_cat" value=""
                                                   {{ !request('product_cat') ? 'checked' : '' }}
                                                   onchange="document.getElementById('filterForm').submit()"/>
                                            <label class="woof_radio_label" for="woof_cat_all">Todas las categorías</label>
                                            <a href="#" data-name="product_cat" data-term-id=""
                                               style="display: none;"
                                               class="woof_radio_term_reset woof_radio_term_reset_all"
                                               onclick="clearCategoryFilter(event)">
                                                <img loading="lazy"
                                                     src="{{ asset('wp-content/plugins/woocommerce-products-filter/img/delete.png') }}"
                                                     height="12" width="12" alt="Eliminar"/>
                                            </a>
                                            <input type="hidden" value="Todas las categorías"
                                                   data-anchor="woof_n_product_cat_all"/>
                                        </li>

                                        @if(isset($categories) && count($categories) > 0)
                                            @foreach($categories as $categorySlug => $categoryName)
                                                <li class="woof_term_15">
                                                    <input type="radio" id="woof_cat_{{ $loop->index }}"
                                                           class="woof_radio_term woof_radio_term"
                                                           data-slug="{{ $categorySlug }}" data-term-id=""
                                                           name="product_cat" value="{{ $categorySlug }}"
                                                           {{ request('product_cat') == $categorySlug ? 'checked' : '' }}
                                                           onchange="document.getElementById('filterForm').submit()"/>
                                                    <label class="woof_radio_label" for="woof_cat_{{ $loop->index }}">{{ $categoryName }}</label>
                                                    <a href="#" data-name="product_cat" data-term-id=""
                                                       style="display: {{ request('product_cat') == $categorySlug ? 'block' : 'none' }};"
                                                       class="woof_radio_term_reset woof_radio_term_reset_15"
                                                       onclick="clearCategoryFilter(event)">
                                                        <img loading="lazy"
                                                             src="{{ asset('wp-content/plugins/woocommerce-products-filter/img/delete.png') }}"
                                                             height="12" width="12" alt="Eliminar"/>
                                                    </a>
                                                    <input type="hidden" value="{{ $categorySlug }}"
                                                           data-anchor="woof_n_product_cat_{{ $categorySlug }}"/>
                                                </li>
                                            @endforeach
                                        @endif

                                    </ul>
                                </div>

                                <script>
                                    // Fonction pour effacer le filtre de catégorie
                                    function clearCategoryFilter(event) {
                                        event.preventDefault();
                                        // Décocher tous les boutons radio
                                        document.querySelectorAll('input[name="product_cat"]').forEach(radio => {
                                            radio.checked = false;
                                        });
                                        // Cocher l'option "Toutes les catégories"
                                        document.getElementById('woof_cat_all').checked = true;
                                        // Soumettre le formulaire
                                        const form = document.getElementById('filterForm');
                                        if (form) {
                                            form.submit();
                                        }
                                    }

                                    // Fonction pour initialiser les filtres depuis l'URL
                                    function initFiltersFromUrl() {
                                        const urlParams = new URLSearchParams(window.location.search);

                                        // Initialiser le slider de prix depuis l'URL
                                        const minPrice = urlParams.get('min_price') || 110;
                                        const maxPrice = urlParams.get('max_price') || 2997;

                                        // Mettre à jour le slider si ionRangeSlider existe
                                        const priceSlider = document.querySelector('.woof_range_slider');
                                        if (priceSlider && typeof $(priceSlider).data('ionRangeSlider') !== 'undefined') {
                                            const sliderInstance = $(priceSlider).data('ionRangeSlider');
                                            sliderInstance.update({
                                                from: parseInt(minPrice),
                                                to: parseInt(maxPrice)
                                            });
                                            priceSlider.value = minPrice + '_' + maxPrice;
                                        }
                                    }

                                    // Initialiser les filtres au chargement de la page
                                    document.addEventListener('DOMContentLoaded', function() {
                                        initFiltersFromUrl();

                                        // Gérer la soumission du formulaire pour le slider de prix
                                        const priceSlider = document.querySelector('.woof_range_slider');
                                        if (priceSlider) {
                                            priceSlider.addEventListener('change', function() {
                                                // Le slider met déjà à jour les champs min_price et max_price
                                                // via le script woof_front.js
                                                const form = document.getElementById('filterForm');
                                                if (form) {
                                                    form.submit();
                                                }
                                            });
                                        }

                                        // Ajouter des champs cachés pour min_price et max_price s'ils n'existent pas
                                        const form = document.getElementById('filterForm');
                                        if (form) {
                                            if (!form.querySelector('input[name="min_price"]')) {
                                                const minInput = document.createElement('input');
                                                minInput.type = 'hidden';
                                                minInput.name = 'min_price';
                                                minInput.value = '{{ request("min_price", 110) }}';
                                                form.appendChild(minInput);
                                            }
                                            if (!form.querySelector('input[name="max_price"]')) {
                                                const maxInput = document.createElement('input');
                                                maxInput.type = 'hidden';
                                                maxInput.name = 'max_price';
                                                maxInput.value = '{{ request("max_price", 2997) }}';
                                                form.appendChild(maxInput);
                                            }
                                        }
                                    });
                                </script>

                                <input type="hidden" name="woof_t_product_cat"
                                       value="Categorías de producto"/>
                                <!-- for red button search nav panel -->
                            </div>
                        </div>
                        <div data-css-class="woof_container_product_tag"
                             class="woof_container woof_container_label woof_container_product_tag woof_container_10 woof_container_producttags  woof_fs_product_tag ">
                            <div class="woof_container_overlay_item"></div>
                            <div class="woof_container_inner woof_container_inner_producttags">
                                <h4>
                                    Etiquetas de producto </h4>

                                <div class="woof_block_html_items">

                                    <ul class="woof_list woof_list_label">
                                    </ul>

                                </div>

                                <input type="hidden" name="woof_t_product_tag"
                                       value="Etiquetas de producto"/>
                                <!-- for red button search nav panel -->
                            </div>
                        </div>
                        <div data-css-class="woof_container_pa_color"
                             class="woof_container woof_container_color woof_container_pa_color woof_container_11 woof_container_productcolor  woof_fs_pa_color ">
                            <div class="woof_container_overlay_item"></div>
                            <div class="woof_container_inner woof_container_inner_productcolor">
                                <h4>
                                    Color del producto </h4>

                                <div class="woof_block_html_items">
                                    <ul class="woof_list woof_list_color " data-type="checkbox">
                                        <li class="woof_color_term_#000000 woof_color_term_37">
                                            <p class="woof_tooltip">
                                                <label class="woof_wcga_label_hide" for="woof_tax_color_black">Negro</label>
                                                <input id="woof_tax_color_black" type="checkbox"
                                                       class="woof_color_term woof_color_term_37"
                                                       data-color="#000000" data-img=""
                                                       data-tax="pa_color" name="pa_color[]"
                                                       value="black"
                                                       {{ in_array('black', request('pa_color', [])) ? 'checked' : '' }}
                                                       onchange="document.getElementById('filterForm').submit()"/>
                                            </p>
                                            <input type="hidden" value="Negro"
                                                   data-anchor="woof_n_pa_color_black"/>
                                        </li>

                                        <li class="woof_color_term_#000000 woof_color_term_39">
                                            <p class="woof_tooltip">
                                                <label class="woof_wcga_label_hide" for="woof_tax_color_brown">Marrón</label>
                                                <input id="woof_tax_color_brown" type="checkbox"
                                                       class="woof_color_term woof_color_term_39"
                                                       data-color="#000000" data-img=""
                                                       data-tax="pa_color" name="pa_color[]"
                                                       value="brown"
                                                       {{ in_array('brown', request('pa_color', [])) ? 'checked' : '' }}
                                                       onchange="document.getElementById('filterForm').submit()"/>
                                            </p>
                                            <input type="hidden" value="Marrón"
                                                   data-anchor="woof_n_pa_color_brown"/>
                                        </li>

                                        <li class="woof_color_term_#ea31a6 woof_color_term_54">
                                            <p class="woof_tooltip">
                                                <label class="woof_wcga_label_hide" for="woof_tax_color_pink">Rosa</label>
                                                <input id="woof_tax_color_pink" type="checkbox"
                                                       class="woof_color_term woof_color_term_54"
                                                       data-color="#ea31a6" data-img=""
                                                       data-tax="pa_color" name="pa_color[]"
                                                       value="pink"
                                                       {{ in_array('pink', request('pa_color', [])) ? 'checked' : '' }}
                                                       onchange="document.getElementById('filterForm').submit()"/>
                                            </p>
                                            <input type="hidden" value="Rosa"
                                                   data-anchor="woof_n_pa_color_pink"/>
                                        </li>
                                    </ul>
                                    <div class="clear clearfix"></div>
                                </div>

                                <input type="hidden" name="woof_t_pa_color"
                                       value="color del producto"/>
                                <!-- for red button search nav panel -->
                            </div>
                        </div>


                        <div class="woof_submit_search_form_container"></div>

                    </div>

                </div>
            </form>

        </div>
    </aside>
</aside>