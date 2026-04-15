@props([
    'onclick' => '',
])
<button class="btn rounded-pill main-btn detail-view-btn py-2 px-5" {{ $attributes->merge(['onclick' => $onclick]) }}>
    {{ $slot }}
</button>
