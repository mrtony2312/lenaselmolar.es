@extends('layouts.app')

@section('title', __('Política de reembolso'))

@push('styles')
@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')

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
                <h1 class="page-title">Política de reembolso</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">

                        <p>En Leñas El Molar C.B., su satisfacción es nuestra prioridad. Si no está completamente
                            satisfecho con su compra, estas son las condiciones de reembolso:</p>



                        <ol class="wp-block-list">
                            <li>Condiciones de Reembolso</li>
                        </ol>



                        <p>Puede solicitar un reembolso si:</p>



                        <p>El producto llega defectuoso o dañado en la entrega</p>



                        <p>El producto recibido no corresponde a su pedido</p>



                        <p>Ejerce su derecho de desistimiento dentro del plazo legal de 14 días naturales, de
                            acuerdo con la Directiva 2011/83/UE y con los artículos 102 y siguientes del Real Decreto
                            Legislativo 1/2007 (Ley General para la Defensa de los Consumidores y Usuarios)</p>



                        <p>El producto debe devolverse en perfectas condiciones y en su embalaje original.</p>



                        <ol start="2" class="wp-block-list">
                            <li>Plazo de Reembolso</li>
                        </ol>



                        <p>Tras la recepción e inspección del producto por nuestro equipo:</p>



                        <p>El reembolso se emitirá en un plazo máximo de 14 días naturales desde que se recibe la
                            devolución o la prueba de su expedición</p>



                        <p>El reembolso se realizará mediante el mismo método de pago utilizado en la compra.</p>



                        <ol start="3" class="wp-block-list">
                            <li>Costes de Devolución</li>
                        </ol>



                        <p>Los costes de devolución corren a cargo del cliente, salvo que el producto esté
                            defectuoso o no corresponda al pedido.</p>



                        <ol start="4" class="wp-block-list">
                            <li>Excepciones al Derecho de Desistimiento</li>
                        </ol>



                        <p>De acuerdo con el artículo 103 del Real Decreto Legislativo 1/2007, no se admite el
                            desistimiento de productos entregados a granel o desprecintados que, por su naturaleza
                            (leña o pellets suministrados sueltos o en sacos abiertos), no puedan devolverse en su
                            estado original. En estos casos solo procede el reembolso si el producto llega
                            defectuoso, dañado o no corresponde al pedido.</p>



                        <ol start="5" class="wp-block-list">
                            <li>Procedimiento de Reembolso</li>
                        </ol>



                        <p>Para iniciar un reembolso:</p>



                        <p>Póngase en contacto con nuestro servicio de atención al cliente por teléfono
                            (+34 679 24 55 97) o mediante el <a href="{{ route('contacto') }}">formulario de contacto</a>.</p>



                        <p>Facilite el número de su pedido y fotografías si el producto presenta algún defecto.</p>



                        <p>Le informaremos del procedimiento de devolución y reembolso.</p>



                        <figure class="wp-block-image size-large is-resized"><a
                                href="/wp-content/uploads/2022/01/er-01-scaled.png"><img loading="lazy" decoding="async"
                                    width="658" height="379" src="/wp-content/uploads/2022/01/er-01-scaled.png"
                                    alt="" class="wp-image-6024" style="width:308px;height:auto" /></a></figure>
                    </div><!-- .site-main -->

                </div><!-- .content-area -->
            </div>
        </section>

    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
@endpush
