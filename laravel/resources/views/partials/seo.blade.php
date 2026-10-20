@php
    $seo = \App\Support\Seo::forPage(request(), get_defined_vars(), trim($__env->yieldContent('title')), setting('company_name') ?: 'Sao Phương Nam');
    $isErrorPage = trim($__env->yieldContent('seo_robots')) === 'noindex';
@endphp
<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if ($isErrorPage)
    <meta name="robots" content="noindex, follow">
@else
    <link rel="canonical" href="{{ $seo['canonical'] }}">
@endif
<meta property="og:locale" content="vi_VN">
<meta property="og:site_name" content="{{ $seo['site_name'] }}">
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:image" content="{{ $seo['image'] }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['description'] }}">
<meta name="twitter:image" content="{{ $seo['image'] }}">
@unless ($isErrorPage)
    <script type="application/ld+json">{!! json_encode(\App\Support\StructuredData::forPage(request(), get_defined_vars(), $seo), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) !!}</script>
@endunless
