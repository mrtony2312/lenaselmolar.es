@extends('layouts.app')

@section('title', __('Política de pago'))

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
                <h1 class="page-title">Política de pago</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">

                        <p>1 – ALOJAMIENTO EN UNA PLATAFORMA SÓLIDA Y PROFESIONAL<br>El <a
                                href="{{ route('home') }}">{{ url('/') }}</a> está alojado en un servidor
                            profesional mantenido por una empresa especializada. Los servidores también se actualizan
                            con regularidad.</p>



                        <p>2 – USO DE UN CERTIFICADO SSL PARA PROTEGER SUS DATOS
                            PERSONALES<br>Este sitio utiliza un certificado SSL para proteger la
                            información transmitida entre usted y el sitio web. Con este certificado, la información se
                            cifra y no puede ser interceptada.</p>



                        <p>Un candado (o icono equivalente) en la barra de direcciones de su navegador indica esto
                            mismo, así como la S en la dirección del sitio: <a href="{{ route('home') }}">{{ url('/') }}</a>
                        </p>



                        <p>3 – RESPONSABILIDAD TOTAL POR EL PAGO Y SU PROCESAMIENTO POR EL BANCO (CONTROL Y
                            CARGO EN CUENTAS BANCARIAS).</p>



                        <p>4 – MEDIOS DE PAGO ACEPTADOS</p>



                        <p>Actualmente el único medio de pago aceptado en este sitio es la
                            <strong>transferencia bancaria</strong>. Tras confirmar el pedido, el cliente recibe
                            por correo electrónico los datos bancarios (titular, IBAN y BIC) de Leñas El Molar C.B.
                            El pedido se prepara una vez confirmada la recepción del pago íntegro.</p>



                        <p>El cliente deberá comprobar con su banco que el importe total del pedido se ha
                            abonado en la cuenta facilitada por Leñas El Molar C.B. y que su nombre y número de pedido
                            se han introducido correctamente en la transferencia.</p>



                        <p>Cualquier comisión bancaria cobrada por el banco del cliente correrá por su cuenta.</p>



                        <figure class="wp-block-image size-large is-resized"><a
                                href="/wp-content/uploads/2022/01/er-01-scaled.png"><img loading="lazy"
                                                                                             decoding="async" width="658" height="379"
                                                                                             src="/wp-content/uploads/2022/01/er-01-scaled.png" alt=""
                                                                                             class="wp-image-6024" style="width:272px;height:auto" /></a></figure>
                    </div><!-- .site-main -->

                </div><!-- .content-area -->
            </div>
        </section>

    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')



@endpush
