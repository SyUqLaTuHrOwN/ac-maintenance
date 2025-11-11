@props(['href' => '#', 'active' => false])

@php
$classes = $active
  ? 'flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-medium'
  : 'flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700';
@endphp

<a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
  {{ $slot }}
</a>
