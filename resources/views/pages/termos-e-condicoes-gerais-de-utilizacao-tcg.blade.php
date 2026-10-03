@extends('layouts.app')

@section('title', __('Términos y condiciones generales de uso TCG'))

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
                <h1 class="page-title">Términos y condiciones generales de uso TCG</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">

                        <h2 class="wp-block-heading">Finalidad</h2>



                        <p>Los presentes Términos Generales de Uso (en adelante &#8220;TCG&#8221;) definen los
                            términos y condiciones de acceso y uso del sitio web <a
                                href="{{ route('home') }}">{{ url('/') }}</a>, operado por Leñas El Molar C.B., así como
                            los derechos y obligaciones de los usuarios.</p>



                        <ol start="2" class="wp-block-list">
                            <li>Avisos Legales</li>
                        </ol>



                        <p><strong>Denominación:&nbsp;</strong>Leñas El Molar C.B.</p>



                        <p><strong>NIF:</strong> E85899003</p>



                        <p><strong>IVA:</strong> ESE85899003</p>



                        <p>Dirección: Calle de la Salud, 4, 28710 El Molar (Madrid), España</p>



                        <ol start="3" class="wp-block-list">
                            <li>Acceso al Sitio</li>
                        </ol>



                        <p>El sitio es accesible gratuitamente a cualquier usuario con acceso a Internet. El
                            usuario es el único responsable de su equipo informático y de la conexión
                            necesaria para utilizar el sitio.</p>



                        <p>Leñas El Molar C.B. se reserva el derecho de suspender, limitar o interrumpir el acceso al sitio
                            por motivos técnicos, de mantenimiento o de seguridad.</p>



                        <ol start="4" class="wp-block-list">
                            <li>Uso del Sitio</li>
                        </ol>



                        <p>El usuario se compromete a utilizar el sitio de acuerdo con la legislación aplicable y
                            con las presentes Condiciones.<br>En particular, el usuario tiene prohibido:</p>



                        <p>Utilizar el sitio con fines ilícitos o fraudulentos,</p>



                        <p>Perjudicar el buen funcionamiento del sitio,</p>



                        <p>Perjudicar los derechos de propiedad intelectual o la imagen de Leñas El Molar C.B.</p>



                        <ol start="5" class="wp-block-list">
                            <li>Productos y Servicios</li>
                        </ol>



                        <p>El sitio <a href="{{ route('home') }}">{{ url('/') }}</a> presenta y ofrece leña,
                            pellets de madera, madera densificada y equipos de calefacción a la venta. Las condiciones
                            de compra, entrega y devolución se especifican en las Condiciones Generales de Venta (CGV)
                            disponibles en el sitio.</p>



                        <ol start="6" class="wp-block-list">
                            <li>Propiedad Intelectual</li>
                        </ol>



                        <p>Todo el contenido del sitio (textos, imágenes, logotipos, vídeos, gráficos, etc.) es
                            propiedad exclusiva de Leñas El Molar C.B., salvo indicación en contrario.<br>Cualquier
                            reproducción, distribución, explotación o modificación, incluso parcial, sin autorización
                            previa por escrito, está estrictamente prohibida.</p>



                        <ol start="7" class="wp-block-list">
                            <li>Datos Personales</li>
                        </ol>



                        <p>Los datos recogidos en el sitio web se tratan de conformidad con el Reglamento General de
                            Protección de Datos (RGPD).<br>Para más información, consulte nuestra Política de
                            Privacidad.</p>



                        <ol start="8" class="wp-block-list">
                            <li>Responsabilidad</li>
                        </ol>



                        <p>Leñas El Molar C.B. hace todo lo posible para garantizar la exactitud y la actualización de la
                            información publicada en el sitio. No obstante, la empresa no puede ser responsabilizada
                            por:
                        </p>



                        <p>Interrupciones, indisponibilidades o averías del sitio;</p>



                        <p>Daños directos o indirectos relacionados con el uso del sitio;</p>



                        <p>La exactitud de la información facilitada por terceros (socios, proveedores, etc.).</p>



                        <ol start="9" class="wp-block-list">
                            <li>Hiperenlaces</li>
                        </ol>



                        <p>El sitio puede contener enlaces a otros sitios. Leñas El Molar C.B. declina cualquier
                            responsabilidad por el contenido y la disponibilidad de estos sitios externos.</p>



                        <ol start="10" class="wp-block-list">
                            <li>Modificación de las Condiciones</li>
                        </ol>



                        <p>Leñas El Molar C.B. se reserva el derecho de modificar las presentes Condiciones en
                            cualquier momento, con el fin de adaptarlas a la evolución legal, técnica o funcional del
                            sitio.<br>La versión aplicable es la que esté en vigor en la fecha en que el usuario
                            navegue por el sitio.</p>



                        <p>11.º Ley Aplicable y Jurisdicción</p>



                        <p>Estas Condiciones están sujetas a la legislación española. En caso de litigio, y en
                            ausencia de una solución amistosa, serán competentes los Juzgados y Tribunales de Vic
                            (Madrid), sin perjuicio del fuero que legalmente corresponda al consumidor.</p>



                        <figure class="wp-block-image size-large is-resized"><a
                                href="/wp-content/uploads/2022/01/er-01-scaled.png"><img loading="lazy" decoding="async"
                                    width="658" height="379" src="/wp-content/uploads/2022/01/er-01-scaled.png"
                                    alt="" class="wp-image-6024" style="width:276px;height:auto" /></a></figure>
                    </div><!-- .site-main -->

                </div><!-- .content-area -->
            </div>
        </section>

    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
@endpush
