@extends('layouts.app')

@section('title', __('Condiciones generales de venta CGV'))

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
                <h1 class="page-title">Condiciones generales de venta CGV</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">

                        <h2 class="wp-block-heading">Preámbulo</h2>



                        <p>Las presentes Condiciones Generales de Venta (en adelante &#8220;CGV&#8221;) rigen las
                            ventas realizadas en el sitio <a href="{{ route('home') }}">{{ url('/') }}</a>, operado
                            por Leñas El Molar C.B., y definen los derechos y obligaciones de las partes en la venta online de
                            leña, pellets de madera, madera densificada y equipos de calefacción (estufas, cocinas y
                            calderas de leña, y estufas de pellets).</p>



                        <p>Al realizar un pedido, el cliente reconoce haber leído y aceptado estas CGV sin reservas.
                        </p>



                        <ol start="2" class="wp-block-list">
                            <li>Información Legal</li>
                        </ol>



                        <p>Nombre: Leñas El Molar C.B.</p>



                        <p><strong>NIF:</strong> E85899003</p>



                        <p><strong>IVA:</strong> ESE85899003</p>



                        <p>Dirección: Calle de la Salud, 4, 28710 El Molar (Madrid), España</p>



                        <ol start="3" class="wp-block-list">
                            <li>Productos</li>
                        </ol>



                        <p>Los productos ofrecidos a la venta se describen y presentan con la mayor precisión
                            posible. Las fotografías son meramente informativas y pueden diferir ligeramente de la
                            realidad debido a la configuración de la pantalla o a los lotes de producción.</p>



                        <ol start="4" class="wp-block-list">
                            <li>Precios</li>
                        </ol>



                        <p>Los precios que figuran en el sitio están expresados en euros, con todos los impuestos
                            incluidos (IVA incluido). Los gastos de envío, si aplican, se comunican antes de confirmar el pedido.<br>Leñas El Molar C.B. se reserva el
                            derecho de modificar sus precios en cualquier momento, si bien los productos se
                            facturarán con base en los precios vigentes en el momento del pedido.</p>



                        <p>5.º Pedido</p>



                        <p>El cliente realiza su pedido seleccionando los productos y confirmando el proceso de
                            compra en línea.<br>El pedido solo se considerará finalizado tras la recepción del pago
                            íntegro.<br>Leñas El Molar C.B. se reserva el derecho de rechazar o cancelar cualquier
                            pedido en caso de disputa de pago o sospecha de fraude.</p>



                        <p>6.º Pago</p>



                        <p>Los métodos de pago aceptados se especifican en el sitio (transferencia bancaria,
                            etc.).<br>Las transacciones están protegidas por un sistema de cifrado para proteger la
                            información bancaria del cliente.</p>



                        <ol start="7" class="wp-block-list">
                            <li>Entrega</li>
                        </ol>



                        <p>{{ \App\Support\ShippingPolicy::summary() }}</p>



                        <p>Los plazos de entrega se ofrecen únicamente a título informativo y pueden variar según
                            el destino.</p>



                        <p>En caso de retraso superior a 30 días, el cliente podrá cancelar su pedido y
                            solicitar el reembolso.</p>



                        <p>Los gastos de entrega se indican en la ficha de producto, en el carrito y antes de confirmar el pedido (actualmente: {{ mb_strtolower(\App\Support\ShippingPolicy::amountLabel()) }}).</p>



                        <p>8.º Derecho de Desistimiento</p>



                        <p>De acuerdo con los artículos 102 y siguientes del Real Decreto Legislativo 1/2007, de 16 de
                            noviembre, por el que se aprueba el texto refundido de la Ley General para la Defensa de los
                            Consumidores y Usuarios, el cliente dispone de 14 días naturales desde la recepción del
                            pedido para ejercer el derecho de desistimiento, sin necesidad de justificación.</p>



                        <p>Determinados productos no son aptos para el derecho de desistimiento, entre ellos:</p>



                        <p>Productos preparados a medida según las indicaciones del cliente (por ejemplo, leña
                            cortada a una longitud específica o pedidos a granel personalizados).</p>



                        <p>Productos precintados que hayan sido desprecintados, montados o utilizados tras la entrega.</p>



                        <p>En caso de desistimiento, el cliente deberá devolver el producto, a su costa, en
                            perfectas condiciones y en su embalaje original.</p>



                        <ol start="9" class="wp-block-list">
                            <li>Garantías</li>
                        </ol>



                        <p>Todos los productos están cubiertos por la garantía legal de conformidad prevista en los
                            artículos 114 y siguientes del Real Decreto Legislativo 1/2007, de 16 de noviembre (texto
                            refundido de la Ley General para la Defensa de los Consumidores y Usuarios).</p>



                        <p>En caso de detectar un defecto, el cliente deberá notificarlo a Leñas El Molar C.B. lo antes
                            posible para gestionar el cambio, la reparación o el reembolso.</p>



                        <ol start="10" class="wp-block-list">
                            <li>Responsabilidad</li>
                        </ol>



                        <p>Leñas El Molar C.B. no se hace responsable de los daños derivados de un uso indebido del
                            producto o de un uso no conforme con su finalidad prevista.<br>La
                            empresa no se hace responsable de los retrasos en la entrega por motivos de fuerza mayor.</p>



                        <ol start="11" class="wp-block-list">
                            <li>Atención al Cliente</li>
                        </ol>



                        <p>Para cualquier duda o reclamación, el cliente puede ponerse en contacto con el servicio
                            de atención al cliente a través de la siguiente dirección:<br>📧  </p>



                        <p>12.º Ley Aplicable y Jurisdicción</p>



                        <p>Estas Condiciones Generales se rigen por la legislación española. En caso de litigio, y
                            en ausencia de una solución amistosa, serán competentes los Juzgados y Tribunales de Vic
                            (Madrid), sin perjuicio del fuero que legalmente corresponda al consumidor.</p>
                    </div><!-- .site-main -->

                </div><!-- .content-area -->
            </div>
        </section>

    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
@endpush
