@extends('layouts.app')

@section('title', 'Contacto | Leñas El Molar')
@section('meta_description', 'Contacta con Leñas El Molar C.B. en El Molar (Madrid): leña de calefacción, leña de encina, distribución de leña y carbón. Teléfono y formulario de contacto.')
@section('canonical', url('contacto'))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ContactPage',
    'url' => url('contacto'),
    'name' => 'Contacto — '.config('app.name'),
    'mainEntity' => [
        '@type' => 'LocalBusiness',
        'name' => config('app.name'),
        'legalName' => 'Leñas El Molar C.B.',
        'vatID' => 'ESE85899003',
        'url' => url('/'),
        'telephone' => '+34679245597',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Calle de la Salud, 4',
            'postalCode' => '28710',
            'addressLocality' => 'El Molar',
            'addressRegion' => 'Madrid',
            'addressCountry' => 'ES',
        ],
        'areaServed' => 'El Molar, Madrid, España',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@push('styles')
    <link rel='stylesheet' id='wpforms-modern-full-css'
        href=' {{ asset('wp-content/plugins/wpforms-lite/assets/css/frontend/modern/wpforms-full.minb35d.css') }}'
        type='text/css' media='all' />
    <style>
        .elementor-kit-7 {
            --e-global-color-primary: #6EC1E4;
            --e-global-color-secondary: #54595F;
            --e-global-color-text: #7A7A7A;
            --e-global-color-accent: #61CE70;
            --e-global-typography-primary-font-family: "Roboto";
            --e-global-typography-primary-font-weight: 600;
            --e-global-typography-secondary-font-family: "Roboto Slab";
            --e-global-typography-secondary-font-weight: 400;
            --e-global-typography-text-font-family: "Roboto";
            --e-global-typography-text-font-weight: 400;
            --e-global-typography-accent-font-family: "Roboto";
            --e-global-typography-accent-font-weight: 500;
        }

        .elementor-section.elementor-section-boxed>.elementor-container {
            max-width: 1356px;
        }

        .e-con {
            --container-max-width: 1356px;
        }

        .elementor-widget:not(:last-child) {
            --kit-widget-spacing: 20px;
        }

        .elementor-element {
            --widgets-spacing: 20px 20px;
            --widgets-spacing-row: 20px;
            --widgets-spacing-column: 20px;
        }

            {}

        h1.page-title {
            display: var(--page-title-display);
        }

        @media(max-width: 1024px) {
            .elementor-section.elementor-section-boxed>.elementor-container {
                max-width: 1024px;
            }

            .e-con {
                --container-max-width: 1024px;
            }
        }

        @media(max-width: 767px) {
            .elementor-section.elementor-section-boxed>.elementor-container {
                max-width: 767px;
            }

            .e-con {
                --container-max-width: 767px;
            }
        }

        .elementor-149 .elementor-element.elementor-element-a9cadde {
            padding: 3px 0px 69px 0px;
        }

        .elementor-149 .elementor-element.elementor-element-3bc2cc2>.elementor-widget-container {
            margin: 0px 0px 16px 10px;
        }

        .elementor-149 .elementor-element.elementor-element-3bc2cc2 .heading-tbay-title {
            text-align: center;
            margin: 0px 0px 11px 0px;
        }

        .elementor-149 .elementor-element.elementor-element-3bc2cc2 .heading-tbay-title .title {
            margin-bottom: 8px;
        }

        .elementor-149 .elementor-element.elementor-element-3bc2cc2 .heading-tbay-title i {
            justify-content: center;
            font-size: 175px;
            line-height: 22px;
        }

        .elementor-149 .elementor-element.elementor-element-3bc2cc2 .heading-tbay-title .description {
            margin-bottom: 0px;
        }

        .elementor-149 .elementor-element.elementor-element-1698e20 {
            margin: 0px 50px calc(var(--kit-widget-spacing, 0px) + 0px) 50px;
            text-align: center;
        }

        .elementor-149 .elementor-element.elementor-element-5f3e4e3 {
            padding: 31px 0px 21px 0px;
        }

        .elementor-149 .elementor-element.elementor-element-0cb4b80 {
            margin-top: 38px;
            margin-bottom: 38px;
        }

        .elementor-149 .elementor-element.elementor-element-582d998 {
            margin: 4px 0px calc(var(--kit-widget-spacing, 0px) + 15px) 3px;
        }

        .elementor-149 .elementor-element.elementor-element-582d998 .elementor-icon-wrapper {
            text-align: left;
        }

        .elementor-149 .elementor-element.elementor-element-582d998.elementor-view-stacked .elementor-icon {
            background-color: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-582d998.elementor-view-framed .elementor-icon,
        .elementor-149 .elementor-element.elementor-element-582d998.elementor-view-default .elementor-icon {
            color: #F55F1E;
            border-color: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-582d998.elementor-view-framed .elementor-icon,
        .elementor-149 .elementor-element.elementor-element-582d998.elementor-view-default .elementor-icon svg {
            fill: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-582d998 .elementor-icon {
            font-size: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-582d998 .elementor-icon svg {
            height: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-bd59c6e>div.elementor-element-populated {
            padding: 0px 0px 0px 2px !important;
        }

        .elementor-149 .elementor-element.elementor-element-ec1cfc1 {
            margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 5px) 0px;
        }

        .elementor-149 .elementor-element.elementor-element-ec1cfc1 .elementor-heading-title {
            font-size: 17px;
            font-weight: 700;
            line-height: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-d8b4bb1 {
            margin: 0px 65px calc(var(--kit-widget-spacing, 0px) + 0px) 0px;
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-149 .elementor-element.elementor-element-06674e9 {
            margin-top: 0px;
            margin-bottom: 38px;
        }

        .elementor-149 .elementor-element.elementor-element-e831ce8 {
            margin: 4px 0px calc(var(--kit-widget-spacing, 0px) + 15px) 3px;
        }

        .elementor-149 .elementor-element.elementor-element-e831ce8 .elementor-icon-wrapper {
            text-align: left;
        }

        .elementor-149 .elementor-element.elementor-element-e831ce8.elementor-view-stacked .elementor-icon {
            background-color: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-e831ce8.elementor-view-framed .elementor-icon,
        .elementor-149 .elementor-element.elementor-element-e831ce8.elementor-view-default .elementor-icon {
            color: #F55F1E;
            border-color: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-e831ce8.elementor-view-framed .elementor-icon,
        .elementor-149 .elementor-element.elementor-element-e831ce8.elementor-view-default .elementor-icon svg {
            fill: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-e831ce8 .elementor-icon {
            font-size: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-e831ce8 .elementor-icon svg {
            height: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-0ab47e9>div.elementor-element-populated {
            padding: 0px 0px 0px 2px !important;
        }

        .elementor-149 .elementor-element.elementor-element-05631fc {
            margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 5px) 0px;
        }

        .elementor-149 .elementor-element.elementor-element-05631fc .elementor-heading-title {
            font-size: 17px;
            font-weight: 700;
            line-height: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-ed2335c {
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-149 .elementor-element.elementor-element-906969c {
            margin-top: 38px;
            margin-bottom: 38px;
        }

        .elementor-149 .elementor-element.elementor-element-ddff410 {
            margin: 4px 0px calc(var(--kit-widget-spacing, 0px) + 15px) 3px;
        }

        .elementor-149 .elementor-element.elementor-element-ddff410 .elementor-icon-wrapper {
            text-align: left;
        }

        .elementor-149 .elementor-element.elementor-element-ddff410.elementor-view-stacked .elementor-icon {
            background-color: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-ddff410.elementor-view-framed .elementor-icon,
        .elementor-149 .elementor-element.elementor-element-ddff410.elementor-view-default .elementor-icon {
            color: #F55F1E;
            border-color: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-ddff410.elementor-view-framed .elementor-icon,
        .elementor-149 .elementor-element.elementor-element-ddff410.elementor-view-default .elementor-icon svg {
            fill: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-ddff410 .elementor-icon {
            font-size: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-ddff410 .elementor-icon svg {
            height: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-d229bf8>div.elementor-element-populated {
            padding: 0px 0px 0px 2px !important;
        }

        .elementor-149 .elementor-element.elementor-element-718728f {
            margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 5px) 0px;
        }

        .elementor-149 .elementor-element.elementor-element-718728f .elementor-heading-title {
            font-size: 17px;
            font-weight: 700;
            line-height: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-6ba2899 {
            margin: 0px 65px calc(var(--kit-widget-spacing, 0px) + 0px) 0px;
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-149 .elementor-element.elementor-element-806382c {
            margin-top: 38px;
            margin-bottom: 38px;
        }

        .elementor-149 .elementor-element.elementor-element-e1514dc {
            margin: 4px 0px calc(var(--kit-widget-spacing, 0px) + 15px) 3px;
        }

        .elementor-149 .elementor-element.elementor-element-e1514dc .elementor-icon-wrapper {
            text-align: left;
        }

        .elementor-149 .elementor-element.elementor-element-e1514dc.elementor-view-stacked .elementor-icon {
            background-color: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-e1514dc.elementor-view-framed .elementor-icon,
        .elementor-149 .elementor-element.elementor-element-e1514dc.elementor-view-default .elementor-icon {
            color: #F55F1E;
            border-color: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-e1514dc.elementor-view-framed .elementor-icon,
        .elementor-149 .elementor-element.elementor-element-e1514dc.elementor-view-default .elementor-icon svg {
            fill: #F55F1E;
        }

        .elementor-149 .elementor-element.elementor-element-e1514dc .elementor-icon {
            font-size: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-e1514dc .elementor-icon svg {
            height: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-f39adb2>div.elementor-element-populated {
            padding: 0px 0px 0px 2px !important;
        }

        .elementor-149 .elementor-element.elementor-element-169d020 {
            margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 5px) 0px;
        }

        .elementor-149 .elementor-element.elementor-element-169d020 .elementor-heading-title {
            font-size: 17px;
            font-weight: 700;
            line-height: 36px;
        }

        .elementor-149 .elementor-element.elementor-element-1f04b7f {
            margin: 0px 65px calc(var(--kit-widget-spacing, 0px) + 0px) 0px;
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-149 .elementor-element.elementor-element-b0ffccf {
            padding: 77px 0px 74px 0px;
        }

        .elementor-149 .elementor-element.elementor-element-3ee5aa7>.elementor-widget-container {
            padding: 0px 0px 24px 0px;
        }

        .elementor-149 .elementor-element.elementor-element-3ee5aa7 .heading-tbay-title {
            text-align: center;
            margin: 23px 0px 0px 0px;
        }

        .elementor-149 .elementor-element.elementor-element-3ee5aa7 .heading-tbay-title .title {
            margin-bottom: 0px;
        }

        .elementor-149 .elementor-element.elementor-element-3ee5aa7 .heading-tbay-title .subtitle {
            margin-bottom: 16px;
        }

        .elementor-149 .elementor-element.elementor-element-3ee5aa7 .heading-tbay-title i {
            justify-content: center;
            font-size: 46px;
        }

        .elementor-149 .elementor-element.elementor-element-3ee5aa7 .heading-tbay-title .description {
            font-size: 14px;
            line-height: 21px;
            color: #555555;
            margin-bottom: 19px;
        }

        @media(max-width: 1024px) {
            .elementor-149 .elementor-element.elementor-element-1698e20 {
                margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 0px) 0px;
            }

            .elementor-149 .elementor-element.elementor-element-0cb4b80 {
                margin-top: 0px;
                margin-bottom: 24px;
            }

            .elementor-149 .elementor-element.elementor-element-582d998 .elementor-icon-wrapper {
                text-align: right;
            }

            .elementor-149 .elementor-element.elementor-element-bd59c6e>div.elementor-element-populated {
                padding: 0px 0px 0px 15px !important;
            }

            .elementor-149 .elementor-element.elementor-element-06674e9 {
                margin-top: 0px;
                margin-bottom: 24px;
            }

            .elementor-149 .elementor-element.elementor-element-e831ce8 .elementor-icon-wrapper {
                text-align: right;
            }

            .elementor-149 .elementor-element.elementor-element-0ab47e9>div.elementor-element-populated {
                padding: 0px 0px 0px 15px !important;
            }

            .elementor-149 .elementor-element.elementor-element-906969c {
                margin-top: 0px;
                margin-bottom: 24px;
            }

            .elementor-149 .elementor-element.elementor-element-ddff410 .elementor-icon-wrapper {
                text-align: right;
            }

            .elementor-149 .elementor-element.elementor-element-d229bf8>div.elementor-element-populated {
                padding: 0px 0px 0px 15px !important;
            }

            .elementor-149 .elementor-element.elementor-element-806382c {
                margin-top: 0px;
                margin-bottom: 24px;
            }

            .elementor-149 .elementor-element.elementor-element-e1514dc .elementor-icon-wrapper {
                text-align: right;
            }

            .elementor-149 .elementor-element.elementor-element-f39adb2>div.elementor-element-populated {
                padding: 0px 0px 0px 15px !important;
            }
        }

        @media(min-width: 768px) {
            .elementor-149 .elementor-element.elementor-element-0c72cc6 {
                width: 25%;
            }

            .elementor-149 .elementor-element.elementor-element-23e35d0 {
                width: 49.332%;
            }

            .elementor-149 .elementor-element.elementor-element-6f6f5ee {
                width: 25%;
            }

            .elementor-149 .elementor-element.elementor-element-9178994 {
                width: 35.055%;
            }

            .elementor-149 .elementor-element.elementor-element-9471841 {
                width: 14%;
            }

            .elementor-149 .elementor-element.elementor-element-bd59c6e {
                width: 86%;
            }

            .elementor-149 .elementor-element.elementor-element-ad5e522 {
                width: 14%;
            }

            .elementor-149 .elementor-element.elementor-element-0ab47e9 {
                width: 86%;
            }

            .elementor-149 .elementor-element.elementor-element-77f4a57 {
                width: 64.56%;
            }

            .elementor-149 .elementor-element.elementor-element-cebc61d {
                width: 14%;
            }

            .elementor-149 .elementor-element.elementor-element-d229bf8 {
                width: 86%;
            }

            .elementor-149 .elementor-element.elementor-element-84486d0 {
                width: 14%;
            }

            .elementor-149 .elementor-element.elementor-element-f39adb2 {
                width: 86%;
            }

            .elementor-149 .elementor-element.elementor-element-1dcab94 {
                width: 16.649%;
            }

            .elementor-149 .elementor-element.elementor-element-1fd9652 {
                width: 66.861%;
            }

            .elementor-149 .elementor-element.elementor-element-37942ce {
                width: 15.822%;
            }
        }

        @media(max-width: 1024px) and (min-width:768px) {
            .elementor-149 .elementor-element.elementor-element-0c72cc6 {
                width: 15%;
            }

            .elementor-149 .elementor-element.elementor-element-23e35d0 {
                width: 70%;
            }

            .elementor-149 .elementor-element.elementor-element-6f6f5ee {
                width: 15%;
            }

            .elementor-149 .elementor-element.elementor-element-9178994 {
                width: 40%;
            }

            .elementor-149 .elementor-element.elementor-element-9471841 {
                width: 20%;
            }

            .elementor-149 .elementor-element.elementor-element-bd59c6e {
                width: 80%;
            }

            .elementor-149 .elementor-element.elementor-element-ad5e522 {
                width: 20%;
            }

            .elementor-149 .elementor-element.elementor-element-0ab47e9 {
                width: 80%;
            }

            .elementor-149 .elementor-element.elementor-element-77f4a57 {
                width: 60%;
            }

            .elementor-149 .elementor-element.elementor-element-cebc61d {
                width: 20%;
            }

            .elementor-149 .elementor-element.elementor-element-d229bf8 {
                width: 80%;
            }

            .elementor-149 .elementor-element.elementor-element-84486d0 {
                width: 20%;
            }

            .elementor-149 .elementor-element.elementor-element-f39adb2 {
                width: 80%;
            }
        }

        @media(max-width: 767px) {
            .elementor-149 .elementor-element.elementor-element-a9cadde {
                padding: 0px 0px 40px 0px;
            }

            .elementor-149 .elementor-element.elementor-element-3bc2cc2>.elementor-widget-container {
                margin: 0px 0px 15px 0px;
            }

            .elementor-149 .elementor-element.elementor-element-3bc2cc2 .heading-tbay-title i {
                font-size: 150px;
                line-height: 20px;
            }

            .elementor-149 .elementor-element.elementor-element-5f3e4e3 {
                padding: 0px 0px 0px 0px;
            }

            .elementor-149 .elementor-element.elementor-element-9471841 {
                width: 15%;
            }

            .elementor-149 .elementor-element.elementor-element-582d998 .elementor-icon-wrapper {
                text-align: left;
            }

            .elementor-149 .elementor-element.elementor-element-bd59c6e {
                width: 85%;
            }

            .elementor-149 .elementor-element.elementor-element-bd59c6e>div.elementor-element-populated {
                padding: 0px 0px 0px 0px !important;
            }

            .elementor-149 .elementor-element.elementor-element-ad5e522 {
                width: 15%;
            }

            .elementor-149 .elementor-element.elementor-element-e831ce8 .elementor-icon-wrapper {
                text-align: left;
            }

            .elementor-149 .elementor-element.elementor-element-0ab47e9 {
                width: 85%;
            }

            .elementor-149 .elementor-element.elementor-element-0ab47e9>div.elementor-element-populated {
                padding: 0px 0px 0px 0px !important;
            }

            .elementor-149 .elementor-element.elementor-element-77f4a57>.elementor-element-populated {
                margin: 30px 0px 0px 0px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px;
            }

            .elementor-149 .elementor-element.elementor-element-cebc61d {
                width: 15%;
            }

            .elementor-149 .elementor-element.elementor-element-ddff410 .elementor-icon-wrapper {
                text-align: left;
            }

            .elementor-149 .elementor-element.elementor-element-d229bf8 {
                width: 85%;
            }

            .elementor-149 .elementor-element.elementor-element-d229bf8>div.elementor-element-populated {
                padding: 0px 0px 0px 0px !important;
            }

            .elementor-149 .elementor-element.elementor-element-84486d0 {
                width: 15%;
            }

            .elementor-149 .elementor-element.elementor-element-e1514dc .elementor-icon-wrapper {
                text-align: left;
            }

            .elementor-149 .elementor-element.elementor-element-f39adb2 {
                width: 85%;
            }

            .elementor-149 .elementor-element.elementor-element-f39adb2>div.elementor-element-populated {
                padding: 0px 0px 0px 0px !important;
            }

            .elementor-149 .elementor-element.elementor-element-b0ffccf {
                padding: 40px 0px 0px 0px;
            }

            .elementor-149 .elementor-element.elementor-element-3ee5aa7>.elementor-widget-container {
                padding: 0px 0px 15px 0px;
            }

            .elementor-149 .elementor-element.elementor-element-3ee5aa7 .heading-tbay-title .subtitle {
                margin-bottom: 8px;
            }

            .elementor-149 .elementor-element.elementor-element-3ee5aa7 .heading-tbay-title {
                margin: 0px 0px 0px 0px;
            }
        }

        .elementor-939 .elementor-element.elementor-element-ab3c07f>.elementor-container>.elementor-column>.elementor-widget-wrap {
            align-content: center;
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-ab3c07f {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 23px 0px 25px 0px;
        }

        .elementor-939 .elementor-element.elementor-element-ab3c07f>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-939 .elementor-element.elementor-element-08d7568>div.elementor-element-populated {
            padding: 0px 10px 0px 20px !important;
        }

        .elementor-939 .elementor-element.elementor-element-8314a1b .toggle-menu-title span {
            line-height: 52px;
        }

        .elementor-939 .elementor-element.elementor-element-8314a1b .toggle-menu-title,
        .elementor-939 .elementor-element.elementor-element-8314a1b .toggle-menu-title>* {
            color: #FFFFFF;
        }

        .elementor-939 .elementor-element.elementor-element-8314a1b .toggle-menu-title {
            background-color: #9E5033;
        }

        .elementor-939 .elementor-element.elementor-element-78a3c28>div.elementor-element-populated {
            padding: 0px 60px 0px 0px !important;
        }

        .elementor-939 .elementor-element.elementor-element-6ed3584 {
            width: var(--container-widget-width, 68.373%);
            max-width: 68.373%;
            --container-widget-width: 68.373%;
            --container-widget-flex-grow: 0;
        }

        .elementor-939 .elementor-element.elementor-element-6ed3584.elementor-element {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-939 .elementor-element.elementor-element-6ed3584 .tbay-search-form .form-group .input-group {
            padding: 9px 0px 9px 0px;
            border-style: solid;
            border-width: 1px 1px 1px 1px;
            border-color: #D7D7D7;
        }

        .elementor-939 .elementor-element.elementor-element-6ed3584 .SumoSelect.open>.optWrapper,
        .elementor-939 .elementor-element.elementor-element-6ed3584 .autocomplete-suggestions {
            margin-top: 1px;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
        .elementor-939 .elementor-element.elementor-element-d3b67e6>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #F8F8F8;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6.elementor-column>.elementor-widget-wrap {
            justify-content: center;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6>.elementor-element-populated {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            margin: 0px 20px 0px 0px;
            --e-column-margin-right: 20px;
            --e-column-margin-left: 0px;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6>.elementor-element-populated>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6>div.elementor-element-populated {
            padding: 0px 18px 0px 0px !important;
        }

        .elementor-939 .elementor-element.elementor-element-d38df21>.elementor-widget-container {
            margin: 0px 0px 0px -6px;
        }

        .elementor-939 .elementor-element.elementor-element-d38df21 .tbay-login a i {
            font-size: 21px !important;
        }

        .elementor-939 .elementor-element.elementor-element-37426c5>.elementor-widget-container {
            margin: 0px 0px 0px 27px;
        }

        .elementor-939 .elementor-element.elementor-element-37426c5 .cart-icon span.mini-cart-items {
            font-size: 13px;
            font-weight: 400;
            background: #E70025;
        }

        .elementor-939 .elementor-element.elementor-element-37426c5 .cart-popup .dropdown-menu.show {
            inset: 54px 1px auto auto !important;
        }

        .rtl .elementor-939 .elementor-element.elementor-element-37426c5 .cart-popup .dropdown-menu.show {
            inset: 54px auto auto 0px !important;
        }

        .elementor-939 .elementor-element.elementor-element-82fbfa0>.elementor-container>.elementor-column>.elementor-widget-wrap {
            align-content: center;
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-82fbfa0:not(.elementor-motion-effects-element-type-background),
        .elementor-939 .elementor-element.elementor-element-82fbfa0>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #FAFAFA;
        }

        .elementor-939 .elementor-element.elementor-element-82fbfa0 {
            box-shadow: 0px 1px 1px 0px #EEEEEE;
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
        }

        .elementor-939 .elementor-element.elementor-element-82fbfa0>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-bc-flex-widget .elementor-939 .elementor-element.elementor-element-c26a08a.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-c26a08a.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main .elementor-item {
            padding: 19px 0px 19px 0px;
        }

        .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main .dropdown-menu .elementor-item {
            padding: 0;
        }

        .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main>.megamenu>li:first-child>.elementor-item,
        .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main>.megamenu>li:first-child>.elementor-item+.sub-menu {
            margin-left: 0;
            left: 0;
        }

        .rtl .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main>.megamenu>li:first-child>.elementor-item,
        .rtl .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main>.megamenu>li:first-child>.elementor-item+.sub-menu {
            margin-right: 0;
            right: 0;
        }

        .elementor-bc-flex-widget .elementor-939 .elementor-element.elementor-element-faa6dd4.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-faa6dd4.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-faa6dd4.elementor-column>.elementor-widget-wrap {
            justify-content: flex-end;
        }

        .elementor-939 .elementor-element.elementor-element-ce2e2b5 .content-empty {
            text-align: center;
        }

        .elementor-939 .elementor-element.elementor-element-ce2e2b5 .product-recently-viewed-header h3 {
            font-size: 15px;
            font-weight: 400;
            line-height: 66px;
            color: #191919;
        }

        .elementor-939 .elementor-element.elementor-element-ce2e2b5 .product-recently-viewed-header:hover h3,
        .elementor-939 .elementor-element.elementor-element-ce2e2b5 .product-recently-viewed-header:hover h3:after {
            color: #F55F1E;
        }

        @media(min-width: 768px) {
            .elementor-939 .elementor-element.elementor-element-08d7568 {
                width: 14.026%;
            }

            .elementor-939 .elementor-element.elementor-element-519d620 {
                width: 17.986%;
            }

            .elementor-939 .elementor-element.elementor-element-78a3c28 {
                width: 50.278%;
            }

            .elementor-939 .elementor-element.elementor-element-d3b67e6 {
                width: 17.675%;
            }

            .elementor-939 .elementor-element.elementor-element-c26a08a {
                width: 59.653%;
            }

            .elementor-939 .elementor-element.elementor-element-faa6dd4 {
                width: 40.313%;
            }
        }

        .elementor-1004 .elementor-element.elementor-element-431f0d8 {
            border-style: solid;
            border-width: 1px 0px 0px 0px;
            border-color: #E7E7E7;
            padding: 82px 0px 40px 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-wrapper {
            align-items: start;
        }

        .elementor-1004 .elementor-element.elementor-element-0b72f39 {
            --icon-box-icon-margin: 21px;
        }

        .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-title {
            margin-block-end: 3px;
            color: #939393;
        }

        .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon {
            font-size: 36px;
        }

        .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-title,
        .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-title a {
            font-size: 17px;
            line-height: 24px;
        }

        .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-description {
            font-family: "Cormorant Garamond", Sans-serif;
            font-size: 32px;
            font-weight: 700;
            line-height: 38px;
            color: #F55F1E;
        }

        .elementor-1004 .elementor-element.elementor-element-2c3fdb5.elementor-column>.elementor-widget-wrap {
            justify-content: center;
        }

        .elementor-1004 .elementor-element.elementor-element-6996e033 {
            margin: 6px 0px calc(var(--kit-widget-spacing, 0px) + 0px) 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-44dea1a {
            --grid-template-columns: repeat(0, auto);
            text-align: right;
            margin: 10px 0px calc(var(--kit-widget-spacing, 0px) + 0px) 0px;
            --icon-size: 18px;
            --grid-column-gap: 8px;
            --grid-row-gap: 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-44dea1a .elementor-social-icon {
            background-color: #F8F8F8;
            --icon-padding: 0.72em;
        }

        .elementor-1004 .elementor-element.elementor-element-44dea1a .elementor-social-icon i {
            color: #F55F1E;
        }

        .elementor-1004 .elementor-element.elementor-element-44dea1a .elementor-social-icon svg {
            fill: #F55F1E;
        }

        .elementor-1004 .elementor-element.elementor-element-44dea1a .elementor-social-icon:hover {
            background-color: #F55F1E;
        }

        .elementor-1004 .elementor-element.elementor-element-44dea1a .elementor-social-icon:hover i {
            color: #FFFFFF;
        }

        .elementor-1004 .elementor-element.elementor-element-44dea1a .elementor-social-icon:hover svg {
            fill: #FFFFFF;
        }

        .elementor-1004 .elementor-element.elementor-element-6ca74e44 {
            padding: 39px 0px 72px 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-6d5e085>.elementor-widget-container {
            margin: 0px 0px 24px 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-6d5e085 .heading-tbay-title {
            text-align: left;
        }

        .elementor-1004 .elementor-element.elementor-element-6d5e085 .heading-tbay-title .title {
            font-family: "Lato", Sans-serif;
            font-size: 17px;
            line-height: 31px;
        }

        .elementor-1004 .elementor-element.elementor-element-6d5e085 .heading-tbay-title i {
            justify-content: center;
            font-size: 46px;
        }

        .elementor-1004 .elementor-element.elementor-element-28eaac98 {
            margin: 0px 85px calc(var(--kit-widget-spacing, 0px) + 16px) 0px;
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-1004 .elementor-element.elementor-element-4d89dafe {
            margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 17px) 0px;
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-1004 .elementor-element.elementor-element-4cf5ce6 {
            margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 17px) 0px;
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-1004 .elementor-element.elementor-element-99b9b26 {
            margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 17px) 0px;
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-1004 .elementor-element.elementor-element-4996a4d {
            margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 17px) 0px;
            font-size: 15px;
            line-height: 27px;
        }

        .elementor-1004 .elementor-element.elementor-element-d16f4e7>.elementor-widget-container {
            margin: 0px 0px 16px 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-d16f4e7 .heading-tbay-title {
            text-align: left;
        }

        .elementor-1004 .elementor-element.elementor-element-d16f4e7 .heading-tbay-title .title {
            font-family: "Lato", Sans-serif;
            font-size: 17px;
            line-height: 31px;
        }

        .elementor-1004 .elementor-element.elementor-element-d16f4e7 .heading-tbay-title i {
            justify-content: center;
            font-size: 46px;
        }

        .elementor-1004 .elementor-element.elementor-element-5cecad7a .heading-tbay-title {
            text-align: center;
        }

        .elementor-1004 .elementor-element.elementor-element-5cecad7a .menu-vertical>li>a {
            font-size: 15px;
            font-weight: 400;
            line-height: 43px;
            color: #939393 !important;
        }

        .elementor-1004 .elementor-element.elementor-element-5cecad7a:hover .menu-vertical>li>a:hover {
            color: #F55F1E !important;
        }

        .elementor-1004 .elementor-element.elementor-element-79852ebd>.elementor-widget-container {
            margin: 0px 0px 16px 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-79852ebd .heading-tbay-title {
            text-align: left;
        }

        .elementor-1004 .elementor-element.elementor-element-79852ebd .heading-tbay-title .title {
            font-family: "Lato", Sans-serif;
            font-size: 17px;
            line-height: 31px;
        }

        .elementor-1004 .elementor-element.elementor-element-79852ebd .heading-tbay-title i {
            justify-content: center;
            font-size: 46px;
        }

        .elementor-1004 .elementor-element.elementor-element-7fbb16c7 .heading-tbay-title {
            text-align: center;
        }

        .elementor-1004 .elementor-element.elementor-element-7fbb16c7 .menu-vertical>li>a {
            font-size: 15px;
            font-weight: 400;
            line-height: 43px;
            color: #939393 !important;
        }

        .elementor-1004 .elementor-element.elementor-element-7fbb16c7:hover .menu-vertical>li>a:hover {
            color: #F55F1E !important;
        }

        .elementor-1004 .elementor-element.elementor-element-7e109280>.elementor-widget-container {
            margin: 0px 0px 16px 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-7e109280 .heading-tbay-title {
            text-align: left;
        }

        .elementor-1004 .elementor-element.elementor-element-7e109280 .heading-tbay-title .title {
            font-family: "Lato", Sans-serif;
            font-size: 17px;
            line-height: 31px;
        }

        .elementor-1004 .elementor-element.elementor-element-7e109280 .heading-tbay-title i {
            justify-content: center;
            font-size: 46px;
        }

        .elementor-1004 .elementor-element.elementor-element-3947eb96 .heading-tbay-title {
            text-align: center;
        }

        .elementor-1004 .elementor-element.elementor-element-3947eb96 .menu-vertical>li>a {
            font-size: 15px;
            font-weight: 400;
            line-height: 43px;
            color: #939393 !important;
        }

        .elementor-1004 .elementor-element.elementor-element-3947eb96:hover .menu-vertical>li>a:hover {
            color: #F55F1E !important;
        }

        .elementor-1004 .elementor-element.elementor-element-28eb7045:not(.elementor-motion-effects-element-type-background),
        .elementor-1004 .elementor-element.elementor-element-28eb7045>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #F5EFE6;
        }

        .elementor-1004 .elementor-element.elementor-element-28eb7045 {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 32px 0px 32px 0px;
        }

        .elementor-1004 .elementor-element.elementor-element-28eb7045>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-bc-flex-widget .elementor-1004 .elementor-element.elementor-element-15c0c939.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-1004 .elementor-element.elementor-element-15c0c939.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-1004 .elementor-element.elementor-element-15c0c939.elementor-column>.elementor-widget-wrap {
            justify-content: flex-start;
        }

        .elementor-bc-flex-widget .elementor-1004 .elementor-element.elementor-element-38f96a14.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-1004 .elementor-element.elementor-element-38f96a14.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-1004 .elementor-element.elementor-element-38f96a14.elementor-column>.elementor-widget-wrap {
            justify-content: flex-end;
        }

        .elementor-1004 .elementor-element.elementor-element-07a93f0 {
            text-align: right;
        }

        @media(max-width: 1024px) {
            .elementor-1004 .elementor-element.elementor-element-0b72f39 {
                --icon-box-icon-margin: 16px;
            }

            .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon {
                font-size: 28px;
            }

            .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-description {
                font-size: 24px;
            }

            .elementor-1004 .elementor-element.elementor-element-44dea1a {
                --icon-size: 16px;
            }

            .elementor-1004 .elementor-element.elementor-element-28eaac98 {
                margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 16px) 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-31f1eb05>.elementor-element-populated {
                margin: 0px 0px 0px 0px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-4ccc6c25>.elementor-element-populated {
                margin: 0px 0px 0px 0px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px;
            }
        }

        @media(max-width: 767px) {
            .elementor-1004 .elementor-element.elementor-element-431f0d8 {
                padding: 40px 0px 0px 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-0b72f39 {
                margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 30px) 0px;
                --icon-box-icon-margin: 8px;
            }

            .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-wrapper {
                text-align: left;
            }

            .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon {
                font-size: 24px;
            }

            .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-title,
            .elementor-1004 .elementor-element.elementor-element-0b72f39 .elementor-icon-box-title a {
                font-size: 15px;
                line-height: 27px;
            }

            .elementor-1004 .elementor-element.elementor-element-2c3fdb5.elementor-column>.elementor-widget-wrap {
                justify-content: flex-start;
            }

            .elementor-1004 .elementor-element.elementor-element-44dea1a {
                text-align: left;
                margin: 40px 0px calc(var(--kit-widget-spacing, 0px) + 0px) 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-6ca74e44 {
                padding: 30px 0px 30px 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-6d5e085>.elementor-widget-container {
                margin: 0px 0px 8px 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-28eaac98 {
                margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 10px) 0px;
                line-height: 27px;
            }

            .elementor-1004 .elementor-element.elementor-element-4d89dafe {
                margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 10px) 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-4cf5ce6 {
                margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 10px) 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-99b9b26 {
                margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 10px) 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-4996a4d {
                margin: 0px 0px calc(var(--kit-widget-spacing, 0px) + 10px) 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-b02c0be>.elementor-element-populated {
                margin: 20px 0px 0px 0px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-d16f4e7>.elementor-widget-container {
                margin: 0px 0px 8px 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-5cecad7a .menu-vertical>li>a {
                font-size: 15px;
                line-height: 27px;
            }

            .elementor-1004 .elementor-element.elementor-element-31f1eb05>.elementor-element-populated {
                margin: 20px 0px 0px 0px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-79852ebd>.elementor-widget-container {
                margin: 0px 0px 8px 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-7fbb16c7 .menu-vertical>li>a {
                font-size: 15px;
                line-height: 27px;
            }

            .elementor-1004 .elementor-element.elementor-element-4ccc6c25>.elementor-element-populated {
                margin: 20px 0px 0px 0px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-7e109280>.elementor-widget-container {
                margin: 0px 0px 8px 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-3947eb96 .menu-vertical>li>a {
                font-size: 15px;
                line-height: 27px;
            }

            .elementor-1004 .elementor-element.elementor-element-15c0c939>.elementor-element-populated {
                margin: 30px 0px 0px 0px;
                --e-column-margin-right: 0px;
                --e-column-margin-left: 0px;
            }

            .elementor-1004 .elementor-element.elementor-element-38f96a14.elementor-column>.elementor-widget-wrap {
                justify-content: flex-start;
            }

            .elementor-1004 .elementor-element.elementor-element-07a93f0 {
                text-align: left;
            }
        }

        @media(min-width: 768px) {
            .elementor-1004 .elementor-element.elementor-element-4b5ac2c7 {
                width: 30.623%;
            }

            .elementor-1004 .elementor-element.elementor-element-b02c0be {
                width: 22.275%;
            }

            .elementor-1004 .elementor-element.elementor-element-31f1eb05 {
                width: 22.072%;
            }
        }

        @media(max-width: 1024px) and (min-width:768px) {
            .elementor-1004 .elementor-element.elementor-element-4b5ac2c7 {
                width: 30%;
            }

            .elementor-1004 .elementor-element.elementor-element-b02c0be {
                width: 20%;
            }

            .elementor-1004 .elementor-element.elementor-element-31f1eb05 {
                width: 25%;
            }

            .elementor-1004 .elementor-element.elementor-element-4ccc6c25 {
                width: 22%;
            }
        }
    </style>
@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')

    <div id="tbay-main-content">
        <section id="tbay-breadcrumb" class="tbay-breadcrumb  breadcrumbs-image"><img
                src="{{ asset('/wp-content/uploads/2022/01/breadcrumb-page-01.jpg') }}" alt="breadcrumb">
            <div class="container">
                <div class="breadscrumb-inner">
                    <h1 class="page-title">Contacto</h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}" class="active">Inicio</a> </li>
                        <li class="active">Página</li>
                    </ol>
                </div>
            </div>
        </section>
        <section id="main-container" class="container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">
                        <div data-elementor-type="wp-page" data-elementor-id="149" class="elementor elementor-149">
                            <section
                                class="elementor-section elementor-top-section elementor-element elementor-element-a9cadde elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                data-id="a9cadde" data-element_type="section"
                                data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                <div class="elementor-container elementor-column-gap-default">
                                    <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-0c72cc6"
                                        data-id="0c72cc6" data-element_type="column">
                                        <div class="elementor-widget-wrap">
                                        </div>
                                    </div>
                                    <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-23e35d0"
                                        data-id="23e35d0" data-element_type="column">
                                        <div class="elementor-widget-wrap elementor-element-populated">
                                            <div class="elementor-element elementor-element-3bc2cc2 elementor-widget elementor-widget-tbay-heading"
                                                data-id="3bc2cc2" data-element_type="widget"
                                                data-widget_type="tbay-heading.default">
                                                <div class="elementor-widget-container">

                                                    <div class="tbay-element tbay-element-heading">
                                                        <h3 class="heading-tbay-title style-1">



                                                            <span class="title">¡Contacta con nosotros!</span>




                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="elementor-element elementor-element-1698e20 elementor-widget elementor-widget-text-editor"
                                                data-id="1698e20" data-element_type="widget"
                                                data-widget_type="text-editor.default">
                                                <p>¿Tienes alguna duda sobre un producto, un pedido o una entrega? Escríbenos y te responderemos lo antes posible.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-6f6f5ee"
                                        data-id="6f6f5ee" data-element_type="column">
                                        <div class="elementor-widget-wrap">
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section
                                class="elementor-section elementor-top-section elementor-element elementor-element-5f3e4e3 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                data-id="5f3e4e3" data-element_type="section"
                                data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                <div class="elementor-container elementor-column-gap-default">
                                    <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-9178994"
                                        data-id="9178994" data-element_type="column">
                                        <div class="elementor-widget-wrap elementor-element-populated">
                                            <section
                                                class="elementor-section elementor-inner-section elementor-element elementor-element-0cb4b80 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                                data-id="0cb4b80" data-element_type="section">
                                                <div class="elementor-container elementor-column-gap-no">
                                                    <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-9471841"
                                                        data-id="9471841" data-element_type="column">
                                                        <div class="elementor-widget-wrap elementor-element-populated">
                                                            <div class="elementor-element elementor-element-582d998 elementor-view-default elementor-widget elementor-widget-icon"
                                                                data-id="582d998" data-element_type="widget"
                                                                data-widget_type="icon.default">
                                                                <div class="elementor-icon-wrapper">
                                                                    <div class="elementor-icon">
                                                                        <i aria-hidden="true"
                                                                            class="tb-icon tb-icon-map"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-bd59c6e"
                                                        data-id="bd59c6e" data-element_type="column">
                                                        <div class="elementor-widget-wrap elementor-element-populated">
                                                            <div class="elementor-element elementor-element-ec1cfc1 elementor-widget elementor-widget-heading"
                                                                data-id="ec1cfc1" data-element_type="widget"
                                                                data-widget_type="heading.default">
                                                                <h2 class="elementor-heading-title elementor-size-default">
                                                                    Dirección</h2>
                                                            </div>
                                                            <div class="elementor-element elementor-element-d8b4bb1 elementor-widget elementor-widget-text-editor"
                                                                data-id="d8b4bb1" data-element_type="widget"
                                                                data-widget_type="text-editor.default">
                                                                <p><strong>Dirección:</strong> Calle de la Salud, 4, 28710 El Molar (Madrid), España</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <section
                                                class="elementor-section elementor-inner-section elementor-element elementor-element-06674e9 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                                data-id="06674e9" data-element_type="section">
                                                <div class="elementor-container elementor-column-gap-no">
                                                    <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-ad5e522"
                                                        data-id="ad5e522" data-element_type="column">
                                                        <div class="elementor-widget-wrap elementor-element-populated">
                                                            <div class="elementor-element elementor-element-e831ce8 elementor-view-default elementor-widget elementor-widget-icon"
                                                                data-id="e831ce8" data-element_type="widget"
                                                                data-widget_type="icon.default">
                                                                <div class="elementor-icon-wrapper">
                                                                    <div class="elementor-icon">
                                                                        <svg aria-hidden="true"
                                                                            class="e-font-icon-svg e-fas-envelope"
                                                                            viewBox="0 0 512 512"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z">
                                                                            </path>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-0ab47e9"
                                                        data-id="0ab47e9" data-element_type="column">
                                                        <div class="elementor-widget-wrap elementor-element-populated">
                                                            <div class="elementor-element elementor-element-05631fc elementor-widget elementor-widget-heading"
                                                                data-id="05631fc" data-element_type="widget"
                                                                data-widget_type="heading.default">
                                                                <h2 class="elementor-heading-title elementor-size-default">
                                                                    Contacto</h2>
                                                            </div>
                                                            <div class="elementor-element elementor-element-ed2335c elementor-widget elementor-widget-text-editor"
                                                                data-id="ed2335c" data-element_type="widget"
                                                                data-widget_type="text-editor.default">
                                                                <p><strong><span style="color: #191919;">Teléfono:</span></strong> +34 679 24 55 97</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="elementor-column elementor-col-66 elementor-top-column elementor-element elementor-element-77f4a57"
                                        data-id="77f4a57" data-element_type="column">
                                        <div class="elementor-widget-wrap elementor-element-populated">

                                            <section
                                                class="elementor-section elementor-inner-section elementor-element elementor-element-906969c elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                                data-id="906969c" data-element_type="section">
                                                <div class="elementor-container elementor-column-gap-no">
                                                    <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-cebc61d"
                                                        data-id="cebc61d" data-element_type="column">
                                                        <div class="elementor-widget-wrap elementor-element-populated">
                                                            <div class="elementor-element elementor-element-ddff410 elementor-view-default elementor-widget elementor-widget-icon"
                                                                data-id="ddff410" data-element_type="widget"
                                                                data-widget_type="icon.default">
                                                                <div class="elementor-icon-wrapper">
                                                                    <div class="elementor-icon">
                                                                        <svg aria-hidden="true"
                                                                            class="e-font-icon-svg e-fab-whatsapp-square"
                                                                            viewBox="0 0 448 512"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M224 122.8c-72.7 0-131.8 59.1-131.9 131.8 0 24.9 7 49.2 20.2 70.1l3.1 5-13.3 48.6 49.9-13.1 4.8 2.9c20.2 12 43.4 18.4 67.1 18.4h.1c72.6 0 133.3-59.1 133.3-131.8 0-35.2-15.2-68.3-40.1-93.2-25-25-58-38.7-93.2-38.7zm77.5 188.4c-3.3 9.3-19.1 17.7-26.7 18.8-12.6 1.9-22.4.9-47.5-9.9-39.7-17.2-65.7-57.2-67.7-59.8-2-2.6-16.2-21.5-16.2-41s10.2-29.1 13.9-33.1c3.6-4 7.9-5 10.6-5 2.6 0 5.3 0 7.6.1 2.4.1 5.7-.9 8.9 6.8 3.3 7.9 11.2 27.4 12.2 29.4s1.7 4.3.3 6.9c-7.6 15.2-15.7 14.6-11.6 21.6 15.3 26.3 30.6 35.4 53.9 47.1 4 2 6.3 1.7 8.6-1 2.3-2.6 9.9-11.6 12.5-15.5 2.6-4 5.3-3.3 8.9-2 3.6 1.3 23.1 10.9 27.1 12.9s6.6 3 7.6 4.6c.9 1.9.9 9.9-2.4 19.1zM400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM223.9 413.2c-26.6 0-52.7-6.7-75.8-19.3L64 416l22.5-82.2c-13.9-24-21.2-51.3-21.2-79.3C65.4 167.1 136.5 96 223.9 96c42.4 0 82.2 16.5 112.2 46.5 29.9 30 47.9 69.8 47.9 112.2 0 87.4-72.7 158.5-160.1 158.5z">
                                                                            </path>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-d229bf8"
                                                        data-id="d229bf8" data-element_type="column">
                                                        <div class="elementor-widget-wrap elementor-element-populated">
                                                            <div class="elementor-element elementor-element-718728f elementor-widget elementor-widget-heading"
                                                                data-id="718728f" data-element_type="widget"
                                                                data-widget_type="heading.default">
                                                                <h2 class="elementor-heading-title elementor-size-default">
                                                                    Whatsapp:</h2>
                                                            </div>
                                                            <div class="elementor-element elementor-element-6ba2899 elementor-widget elementor-widget-text-editor"
                                                                data-id="6ba2899" data-element_type="widget"
                                                                data-widget_type="text-editor.default">
                                                                <p><strong>+34 679 24 55 97 </strong></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <section
                                                class="elementor-section elementor-inner-section elementor-element elementor-element-806382c elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                                data-id="806382c" data-element_type="section">
                                                <div class="elementor-container elementor-column-gap-no">
                                                    <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-84486d0"
                                                        data-id="84486d0" data-element_type="column">
                                                        <div class="elementor-widget-wrap elementor-element-populated">
                                                            <div class="elementor-element elementor-element-e1514dc elementor-view-default elementor-widget elementor-widget-icon"
                                                                data-id="e1514dc" data-element_type="widget"
                                                                data-widget_type="icon.default">
                                                                <div class="elementor-icon-wrapper">
                                                                    <div class="elementor-icon">
                                                                        <i aria-hidden="true"
                                                                            class="tb-icon tb-icon-free-delivery"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-f39adb2"
                                                        data-id="f39adb2" data-element_type="column">
                                                        <div class="elementor-widget-wrap elementor-element-populated">
                                                            <div class="elementor-element elementor-element-169d020 elementor-widget elementor-widget-heading"
                                                                data-id="169d020" data-element_type="widget"
                                                                data-widget_type="heading.default">
                                                                <h2 class="elementor-heading-title elementor-size-default">
                                                                    Envío</h2>
                                                            </div>
                                                            <div class="elementor-element elementor-element-1f04b7f elementor-widget elementor-widget-text-editor"
                                                                data-id="1f04b7f" data-element_type="widget"
                                                                data-widget_type="text-editor.default">
                                                                <p><strong>🚚 {{ \App\Support\ShippingPolicy::shortLabel() }}</strong></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section
                                class="elementor-section elementor-top-section elementor-element elementor-element-b0ffccf elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                data-id="b0ffccf" data-element_type="section"
                                data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                <div class="elementor-container elementor-column-gap-default">
                                    <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-1dcab94"
                                        data-id="1dcab94" data-element_type="column">
                                        <div class="elementor-widget-wrap">
                                        </div>
                                    </div>
                                    <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-1fd9652"
                                        data-id="1fd9652" data-element_type="column">
                                        <div class="elementor-widget-wrap elementor-element-populated">
                                            <div class="elementor-element elementor-element-3ee5aa7 elementor-widget elementor-widget-tbay-heading"
                                                data-id="3ee5aa7" data-element_type="widget"
                                                data-widget_type="tbay-heading.default">
                                                <div class="elementor-widget-container">

                                                    <div class="tbay-element tbay-element-heading">
                                                        <h3 class="heading-tbay-title style-1">

                                                            <span class="subtitle">Contáctanos</span>


                                                            <span class="title">Enviar un mensaje</span>



                                                            <i aria-hidden="true"
                                                                class="tb-icon tb-icon-check-circle"></i>
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="elementor-element elementor-element-783b1cb elementor-widget elementor-widget-wpforms"
                                                data-id="783b1cb" data-element_type="widget"
                                                data-widget_type="wpforms.default">
                                                <div class="elementor-widget-container">
                                                    <div class="wpforms-container wpforms-container-full wpforms-render-modern"
                                                        id="wpforms-5449">
                                                        <form id="wpforms-form-5449"
                                                            class="wpforms-form"
                                                            data-formid="5449" method="post"
                                                            action="{{ route('contacto.send') }}">
                                                            @csrf
                                                            @if (session('contact_success'))
                                                                <div class="wpforms-confirmation-container-full"
                                                                    style="padding:14px 16px;border-radius:8px;background:#eaf6ec;color:#1e7a34;margin-bottom:16px">
                                                                    {{ session('contact_success') }}
                                                                </div>
                                                            @endif
                                                            @if (session('contact_error'))
                                                                <div
                                                                    style="padding:14px 16px;border-radius:8px;background:#ffdad6;color:#ba1a1a;margin-bottom:16px">
                                                                    {{ session('contact_error') }}
                                                                </div>
                                                            @endif
                                                            @if ($errors->any())
                                                                <div
                                                                    style="padding:14px 16px;border-radius:8px;background:#ffdad6;color:#ba1a1a;margin-bottom:16px">
                                                                    <ul style="margin:0;padding-left:18px">
                                                                        @foreach ($errors->all() as $e)
                                                                            <li>{{ $e }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                            <div class="wpforms-field-container">
                                                                <div id="wpforms-5449-field_1-container"
                                                                    class="wpforms-field wpforms-field-name"
                                                                    data-field-id="1">
                                                                    <fieldset>
                                                                        <legend class="wpforms-field-label">Nombre
                                                                            <span class="wpforms-required-label"
                                                                                aria-hidden="true">*</span>
                                                                        </legend>
                                                                        <div
                                                                            class="wpforms-field-row wpforms-field-medium">
                                                                            <div
                                                                                class="wpforms-field-row-block wpforms-first wpforms-one-half">
                                                                                <input type="text"
                                                                                    id="wpforms-5449-field_1"
                                                                                    class="wpforms-field-name-first wpforms-field-required"
                                                                                    name="wpforms[fields][1][first]"
                                                                                    placeholder="Nombre"
                                                                                    aria-errormessage="wpforms-5449-field_1-error"
                                                                                    required><label
                                                                                    for="wpforms-5449-field_1"
                                                                                    class="wpforms-field-sublabel after wpforms-sublabel-hide">First</label>
                                                                            </div>
                                                                            <div
                                                                                class="wpforms-field-row-block wpforms-one-half">
                                                                                <input type="text"
                                                                                    id="wpforms-5449-field_1-last"
                                                                                    class="wpforms-field-name-last wpforms-field-required"
                                                                                    name="wpforms[fields][1][last]"
                                                                                    placeholder="Apellidos"
                                                                                    aria-errormessage="wpforms-5449-field_1-last-error"
                                                                                    required><label
                                                                                    for="wpforms-5449-field_1-last"
                                                                                    class="wpforms-field-sublabel after wpforms-sublabel-hide">Last</label>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                                <div id="wpforms-5449-field_5-container"
                                                                    class="wpforms-field wpforms-field-text"
                                                                    data-field-type="text" data-field-id="5">
                                                                    <label class="wpforms-field-label"
                                                                        for="wpforms-5449-field_5">Email o
                                                                        nombre</label>
                                                                    <input type="text" id="wpforms-5449-field_5"
                                                                        class="wpforms-field-medium"
                                                                        name="wpforms[fields][5]">
                                                                </div>
                                                                <div id="wpforms-5449-field_2-container"
                                                                    class="wpforms-field wpforms-field-email"
                                                                    data-field-id="2"><label class="wpforms-field-label"
                                                                        for="wpforms-5449-field_2">E-mail <span
                                                                            class="wpforms-required-label"
                                                                            aria-hidden="true">*</span></label><input
                                                                        type="email" id="wpforms-5449-field_2"
                                                                        class="wpforms-field-medium wpforms-field-required"
                                                                        name="wpforms[fields][2]" spellcheck="false"
                                                                        aria-errormessage="wpforms-5449-field_2-error"
                                                                        required></div>
                                                                <div id="wpforms-5449-field_4-container"
                                                                    class="wpforms-field wpforms-field-text"
                                                                    data-field-id="4"><label class="wpforms-field-label"
                                                                        for="wpforms-5449-field_4">Asunto</label><input
                                                                        type="text" id="wpforms-5449-field_4"
                                                                        class="wpforms-field-medium"
                                                                        name="wpforms[fields][4]"
                                                                        aria-errormessage="wpforms-5449-field_4-error">
                                                                </div>
                                                                <div id="wpforms-5449-field_3-container"
                                                                    class="wpforms-field wpforms-field-textarea"
                                                                    data-field-id="3"><label class="wpforms-field-label"
                                                                        for="wpforms-5449-field_3">Comentario o
                                                                        mensaje</label>
                                                                    <textarea id="wpforms-5449-field_3" rows="5" class="wpforms-field-medium" name="wpforms[fields][3]"
                                                                        aria-errormessage="wpforms-5449-field_3-error"></textarea>
                                                                </div>

                                                            </div><!-- .wpforms-field-container -->
                                                            <div class="wpforms-submit-container">
                                                                <button type="submit" name="wpforms[submit]"
                                                                    id="wpforms-submit-5449" class="wpforms-submit"
                                                                    data-alt-text="Enviando..." data-submit-text="Enviar"
                                                                    aria-live="assertive"
                                                                    value="wpforms-submit">Enviar</button>
                                                            </div>
                                                        </form>
                                                    </div> <!-- .wpforms-container -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </section>
                        </div>
                    </div><!-- .site-main -->

                </div><!-- .content-area -->
            </div>
        </section>

    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
@endpush
