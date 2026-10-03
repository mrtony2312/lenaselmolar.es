@extends('layouts.app')

@section('title', __('Avisos legales'))

@push('styles')
@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')
    @php($nap = config('merchant.nap'))
    <div id="tbay-main-content">
        <section id="tbay-breadcrumb" class="tbay-breadcrumb  breadcrumbs-text active-nav-right show-title">
            <div class="container">
                <div class="breadscrumb-inner">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}" class="active">Inicio</a> </li>
                        <li class="active">Página</li>
                    </ol>
                </div>
            </div>
        </section>
        <div class="title-not-breadcrumbs">
            <div class="container">
                <h1 class="page-title">Avisos legales</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">

                        <p>Información facilitada en cumplimiento del artículo 10 de la Ley 34/2002, de 11 de julio, de
                            Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE).</p>

                        <p>{{ $nap['legal_name'] }}<br>
                            Nombre comercial: {{ $nap['commercial_name'] }}<br>
                            También conocida como: {{ $nap['alternate_name'] }}<br>
                            Forma jurídica: {{ $nap['legal_form'] }}<br>
                            <strong>Dirección:</strong> {{ $nap['address_line'] }}</p>

                        <p>
                            <strong>Teléfono:</strong> {{ $nap['telephone_display'] }}
                            @if (!empty($nap['email']))
                                <br><strong>Correo electrónico:</strong> {{ $nap['email'] }}
                            @endif
                        </p>

                        <p><strong>CIF:</strong> {{ $nap['tax_id'] }}</p>

                        <p><strong>IVA (identificador fiscal):</strong> {{ $nap['vat_id'] }}</p>

                        <p>
                            <strong>{{ $nap['legal_name'] }}</strong> es una {{ $nap['legal_form'] }}
                            con domicilio en {{ $nap['locality'] }} ({{ $nap['region'] }}), España.
                            Actividad indicada desde {{ $nap['activity_since'] }}.
                            <br>Actividades: venta de leña de calefacción, venta al por mayor de leña,
                            leña para estufas, chimeneas, sistemas de calefacción y barbacoa,
                            distribución de leña y venta de carbón.
                            <br>CNAE indicado: {{ $nap['cnae'] }} — {{ $nap['cnae_label'] }}
                            (referencia administrativa; no implica una afirmación sobre capacidad de producción).
                        </p>

                        <p>El contenido de estas páginas ha sido elaborado con el máximo cuidado. No obstante, no
                            asumimos responsabilidad alguna por la exactitud, integridad o actualidad de este contenido.</p>

                        <p>Derechos de Autor</p>

                        <p>El contenido de este sitio (texto e imágenes) se pone a disposición de los internautas
                            exclusivamente para su uso privado. Cualquier uso comercial del contenido requiere la
                            autorización por escrito de {{ $nap['legal_name'] }}. El operador de este sitio se
                            reserva el derecho exclusivo de utilización del texto y de las imágenes. Quedan
                            excluidas las imágenes no modificadas y libres de derechos.</p>

                        <p>Propiedad Intelectual:</p>

                        <p>Todo el contenido de este sitio, incluyendo, entre otros, textos, imágenes, gráficos,
                            logotipos, vídeos y todos los demás elementos que contiene, está protegido por las leyes
                            de propiedad intelectual y pertenece exclusivamente a {{ $nap['legal_name'] }}, salvo indicación en
                            contrario.</p>

                        <p>Cualquier reproducción, representación, modificación, publicación o adaptación de la
                            totalidad o parte de los elementos del sitio, por cualquier medio o procedimiento, está
                            prohibida sin la autorización previa por escrito de {{ $nap['legal_name'] }}. Cualquier uso no
                            autorizado del sitio o de sus elementos constituye una infracción y será perseguido de
                            acuerdo con la legislación aplicable.</p>

                        <p>Hiperenlaces:</p>

                        <p>El sitio puede contener hiperenlaces a sitios de terceros. {{ $nap['legal_name'] }} no tiene ningún
                            control sobre estos sitios y declina cualquier responsabilidad por su contenido y
                            políticas de privacidad.</p>

                        <p>Datos de contacto:</p>

                        <p>Dirección postal: {{ $nap['legal_name'] }} — {{ $nap['address_line'] }}</p>

                        <p>Teléfono: {{ $nap['telephone_display'] }}</p>

                        @if (!empty($nap['email']))
                            <p>Correo electrónico: {{ $nap['email'] }}</p>
                        @endif

                        <figure class="wp-block-image size-large is-resized"><a
                                href="/wp-content/uploads/2022/01/er-01-scaled.png"><img loading="lazy" decoding="async"
                                    width="658" height="379" src="/wp-content/uploads/2022/01/er-01-scaled.png"
                                    alt="{{ $nap['commercial_name'] }}" class="wp-image-6024" style="width:273px;height:auto" /></a></figure>
                    </div><!-- .site-main -->

                </div><!-- .content-area -->
            </div>
        </section>

    </div>


    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
@endpush
