@extends('layouts.app')

@section('title', __('Política de cookies'))

@section('content')
    @include('layouts.partials.navbar.public-show')
    @php($nap = config('merchant.nap'))

    <div id="tbay-main-content">
        <section id="tbay-breadcrumb" class="tbay-breadcrumb breadcrumbs-text active-nav-right show-title">
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
                <h1 class="page-title">Política de cookies</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">
                        <p>
                            Esta página describe las cookies que usa el sitio de
                            <strong>{{ $nap['legal_name'] }}</strong>.
                        </p>

                        <p>1. Cookies técnicas</p>
                        <p>
                            El sitio usa una cookie de sesión para el carrito, la lista de deseos y el
                            formulario de pedido, y una cookie de seguridad del formulario (CSRF).
                            Son necesarias para que la tienda funcione. No sirven para publicidad.
                        </p>

                        <p>2. Publicidad, solo si la acepta</p>
                        <p>
                            El sitio carga una etiqueta de Google Ads. Sirve para medir los anuncios.
                            No se activa el almacenamiento publicitario hasta que pulse «Aceptar todas»
                            en el aviso de cookies. «Rechazar no necesarias» lo deja desactivado.
                            No hay Google Analytics ni cookies de redes sociales.
                        </p>

                        <p>3. Contacto</p>
                        <p>
                            Para cualquier pregunta:
                            <a href="mailto:{{ $nap['email'] }}">{{ $nap['email'] }}</a>
                            o {{ $nap['telephone_display'] }}.
                            Más información sobre los datos personales en la
                            <a href="{{ route('politica-de-privacidade') }}">política de privacidad</a>.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('layouts.partials.footer.public')
@endsection
