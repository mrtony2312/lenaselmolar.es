@extends('layouts.app')

@section('title', $page['title'])
@section('meta_description', $page['meta'])
@section('canonical', url($slug))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $page['h1'], 'item' => url($slug)],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@if (!empty($page['faqs']))
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($page['faqs'])->map(fn ($q) => [
        '@type' => 'Question',
        'name' => $q[0],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q[1]],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
@endpush

@push('styles')
<style>
    .lv-landing { max-width: 1100px; margin: 0 auto; padding: 32px 20px 56px; }
    .lv-landing__crumb { font-size: .85rem; color: #777; margin-bottom: 16px; }
    .lv-landing__crumb a { color: #777; text-decoration: none; }
    .lv-landing h1 { font-size: 1.9rem; line-height: 1.25; margin: 0 0 16px; }
    .lv-landing__intro { font-size: 1.05rem; }
    .lv-landing__intro p, .lv-landing__faq p, .lv-landing li { line-height: 1.7; color: #333; }
    .lv-landing__intro a, .lv-landing__faq a { color: #F55F1E; }
    .lv-landing h2 { font-size: 1.35rem; margin: 36px 0 14px; }
    .lv-product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
    .lv-landing__cta { margin: 28px 0 0; }
    .lv-btn { display: inline-block; padding: 12px 22px; border-radius: 6px; text-decoration: none; font-weight: 600; }
    .lv-btn--primary { background: #F55F1E; color: #fff; }
    .lv-landing__faq details { border-bottom: 1px solid #e2e2e2; padding: 12px 0; }
    .lv-landing__faq summary { cursor: pointer; font-weight: 600; }
    .lv-landing__faq details p { margin: 10px 0 0; }
    @media (max-width: 900px) { .lv-product-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .lv-landing h1 { font-size: 1.5rem; } .lv-product-grid { grid-template-columns: 1fr 1fr; gap: 14px; } }
</style>
@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')

    <div class="lv-landing">
        <nav class="lv-landing__crumb" aria-label="Migas de pan">
            <a href="{{ route('home') }}">Inicio</a> / <span>{{ $page['h1'] }}</span>
        </nav>

        <h1>{{ $page['h1'] }}</h1>

        <div class="lv-landing__intro">
            {!! $page['intro'] !!}
        </div>

        @if ($products->isNotEmpty())
            <h2>Productos destacados</h2>
            <div class="lv-product-grid">
                @foreach ($products->take(12) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="lv-landing__cta">
                <a class="lv-btn lv-btn--primary"
                    href="{{ route('category', ['category' => $page['source_category']]) }}">Ver toda la categoría</a>
            </div>
        @endif

        @if (!empty($page['faqs']))
            <h2>Preguntas frecuentes</h2>
            <div class="lv-landing__faq">
                @foreach ($page['faqs'] as $faq)
                    <details>
                        <summary>{{ $faq[0] }}</summary>
                        <p>{{ $faq[1] }}</p>
                    </details>
                @endforeach
            </div>
        @endif
    </div>

    @include('layouts.partials.footer.public')
@endsection
