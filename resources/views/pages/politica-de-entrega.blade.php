@extends('layouts.app')

@section('title', __('Política de entrega'))

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
                <h1 class="page-title">Política de entrega</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">

                        <p>
                            En <strong>{{ $nap['legal_name'] }}</strong> ({{ $nap['commercial_name'] }})
                            existe una actividad de distribución / entrega local de leña y carbón.
                            Las condiciones concretas de cada pedido se confirman antes de la entrega.
                        </p>

                        <ol class="wp-block-list">
                            <li>Zonas de entrega</li>
                        </ol>

                        @if (\App\Support\ShippingPolicy::confirmed())
                            <p>Entregamos en toda España.</p>
                        @else
                            <p>
                                La cobertura de entrega no se publica aquí de forma genérica
                                («toda España» u otras afirmaciones no confirmadas).
                                Indícanos tu código postal o localidad a través de la
                                <a href="{{ route('contacto') }}">página de contacto</a>
                                o por teléfono ({{ $nap['telephone_display'] }}) y te confirmaremos
                                si podemos servir tu zona y en qué condiciones.
                            </p>
                        @endif

                        <ol start="2" class="wp-block-list">
                            <li>Plazos y costes</li>
                        </ol>

                        {{-- Same text as the cart, checkout, product page and feed (App\Support\ShippingPolicy). --}}
                        <p>{{ \App\Support\ShippingPolicy::summary() }}</p>

                        <ol start="3" class="wp-block-list">
                            <li>Entrega de cargas voluminosas</li>
                        </ol>

                        <p>
                            La leña y el carbón pueden entregarse en formatos adaptados al pedido
                            (por ejemplo sobre palé) cuando así se acuerde. El acceso del vehículo,
                            el punto de descarga y la presencia del cliente deben acordarse previamente.
                        </p>

                        <ol start="4" class="wp-block-list">
                            <li>Incidencias</li>
                        </ol>

                        <p>
                            Si detectas un problema en la entrega, contacta con nosotros lo antes posible
                            por teléfono ({{ $nap['telephone_display'] }}) o mediante el
                            <a href="{{ route('contacto') }}">formulario de contacto</a>,
                            aportando fotos si hay daños visibles.
                        </p>

                        <ol start="5" class="wp-block-list">
                            <li>Devoluciones</li>
                        </ol>

                        <p>
                            Consulta la <a href="{{ route('politicaDeReembolso') }}">política de reembolso</a>
                            para las condiciones aplicables.
                        </p>

                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
@endpush
