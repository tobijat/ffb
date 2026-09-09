@props([
    'code' => '',
    'title' => '',
    'base' => null,
])

{!! \App\Support\Flag::html($code, array_filter([
    'title' => $title !== '' ? $title : null,
    'base' => $base,
    'class' => $attributes->get('class'),
], static fn ($value) => $value !== null && $value !== '')) !!}
