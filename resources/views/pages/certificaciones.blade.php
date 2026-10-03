@extends('layouts.app')

@section('title', __('Certificaciones'))

@push('styles')
    <style>
        .lv-certs { display: grid; gap: 20px; margin: 24px 0 40px; }
        .lv-cert-card {
            border: 1px solid var(--lv-border, #e5e7eb);
            border-radius: 12px;
            padding: 20px 22px;
            background: var(--lv-surface, #fff);
        }
        .lv-cert-card h2 { margin: 0 0 4px; font-size: 20px; }
        .lv-cert-card__scope { margin: 0 0 14px; color: var(--lv-ink-soft, #555); }
        .lv-cert-card dl {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 6px 16px;
            margin: 0 0 14px;
            font-size: 14.5px;
        }
        .lv-cert-card dt { color: var(--lv-ink-soft, #555); font-weight: 600; }
        .lv-cert-card dd { margin: 0; }
        .lv-cert-card__pdf {
            display: inline-block;
            padding: 9px 16px;
            border-radius: 8px;
            background: var(--lv-primary, #d97706);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .lv-cert-card__pdf--muted {
            background: transparent;
            color: var(--lv-ink-soft, #555);
            border: 1px dashed var(--lv-border, #cbd5e1);
        }
        @media (max-width: 600px) {
            .lv-cert-card dl { grid-template-columns: 1fr; gap: 2px 0; }
            .lv-cert-card dt { margin-top: 8px; }
        }
    </style>
@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')

    <div id="tbay-main-content">
        <section id="tbay-breadcrumb" class="tbay-breadcrumb  breadcrumbs-text active-nav-right show-title">
            <div class="container">
                <div class="breadscrumb-inner">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}" class="active">Inicio</a> </li>
                        <li class="active">Certificaciones</li>
                    </ol>
                </div>
            </div>
        </section>
        <div class="title-not-breadcrumbs">
            <div class="container">
                <h1 class="page-title">Certificaciones</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">

                        <p>Leñas El Molar C.B. comercializa productos de
                            calefacción a base de biomasa que cumplen las principales normas de calidad europeas.
                            A continuación se detallan las certificaciones de la empresa y de sus productos.</p>

                        <div class="lv-certs">
                            @foreach ($certificaciones as $cert)
                                <div class="lv-cert-card" id="{{ $cert['key'] }}">
                                    <h2>{{ $cert['name'] }}</h2>
                                    <p class="lv-cert-card__scope">{{ $cert['scope'] }}</p>
                                    <dl>
                                        <dt>Organismo emisor</dt>
                                        <dd>{{ $cert['issuer'] }}</dd>
                                        <dt>Número de certificado</dt>
                                        <dd>{{ $cert['number'] }}</dd>
                                        <dt>Validez</dt>
                                        <dd>{{ $cert['validity'] }}</dd>
                                    </dl>
                                    @if (!empty($cert['pdf']))
                                        <a class="lv-cert-card__pdf" href="{{ asset($cert['pdf']) }}" target="_blank"
                                            rel="noopener">Descargar certificado (PDF)</a>
                                    @else
                                        <span class="lv-cert-card__pdf lv-cert-card__pdf--muted">Documento disponible
                                            bajo petición</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <p>Para solicitar una copia de cualquiera de estos certificados, escríbanos a
                            .</p>

                    </div><!-- .site-main -->
                </div>
            </div>
        </section>
    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
@endpush
