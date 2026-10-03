@extends('layouts.app')

@section('title', __('Sobre nosotros'))
@section('meta_description', 'Leñas El Molar C.B. (Leñas y Carbones El Molar): venta de leña de calefacción, leña de encina, leña para estufa y chimenea, distribución de leña y carbón en El Molar (Madrid).')

@push('styles')
@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')
    @php($nap = config('merchant.nap'))

    <div id="tbay-main-content">
        <section id="tbay-breadcrumb" class="tbay-breadcrumb  breadcrumbs-image"><img
                src="../wp-content/uploads/2022/01/breadcrumb-page-02.jpg"
                alt="breadcrumb">
            <div class="container">
                <div class="breadscrumb-inner">
                    <h1 class="page-title">Sobre nosotros</h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}" class="active">Inicio</a> </li>
                        <li class="active">Página</li>
                    </ol>
                </div>
            </div>
        </section>
        <section id="main-container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">
                        <div class="container" style="max-width:900px;padding:40px 20px 64px;">
                            <h2>{{ $nap['commercial_name'] }}</h2>
                            <p>
                                <strong>{{ $nap['legal_name'] }}</strong>
                                (también {{ $nap['alternate_name'] }}) es una {{ $nap['legal_form'] }}
                                con domicilio en {{ $nap['address_line'] }}.
                                Actividad indicada desde {{ $nap['activity_since'] }}.
                            </p>
                            <p>
                                CIF: <strong>{{ $nap['tax_id'] }}</strong>.
                                Teléfono: <strong>{{ $nap['telephone_display'] }}</strong>.
                            </p>

                            <h2>Nuestra actividad</h2>
                            <p>
                                Presentamos la actividad comercial de {{ $nap['commercial_name'] }} en torno a la leña y el carbón:
                            </p>
                            <ul>
                                <li>Venta de leña de calefacción</li>
                                <li>Venta al por mayor de leña</li>
                                <li>Leña para estufas</li>
                                <li>Leña para chimeneas</li>
                                <li>Leña para sistemas de calefacción</li>
                                <li>Leña para barbacoa</li>
                                <li>Distribución de leña</li>
                                <li>Venta de carbón</li>
                            </ul>

                            <h2>Productos</h2>
                            <p>
                                En fuentes profesionales se mencionan, entre otros:
                                leña de calefacción, leña de encina, leña para estufas, leña para chimeneas,
                                leña para calefacción, leña para barbacoa y carbón.
                            </p>
                            <p>
                                Los pesos, dimensiones, humedad, origen, certificaciones, envases o precios
                                se indican únicamente cuando están confirmados en cada ficha de producto.
                            </p>

                            <h2>Venta al por mayor y distribución</h2>
                            <p>
                                La venta al por mayor y la distribución / entrega local están referenciadas.
                                Las condiciones concretas (zonas, plazos, formatos de pedido o mínimos)
                                se confirman bajo petición: no publicamos capacidades, tarifas ni formatos
                                de embalaje no verificados.
                            </p>

                            <h2>CNAE</h2>
                            <p>
                                CNAE indicado: <strong>{{ $nap['cnae'] }}</strong> — {{ $nap['cnae_label'] }}.
                                Esta referencia administrativa no constituye una afirmación sobre la capacidad
                                real de producción de la empresa.
                            </p>

                            <h2>Contacto</h2>
                            <p>
                                Dirección: {{ $nap['address_line'] }}<br>
                                Teléfono: {{ $nap['telephone_display'] }}<br>
                                <a href="{{ route('contacto') }}">Formulario de contacto</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
@endpush
