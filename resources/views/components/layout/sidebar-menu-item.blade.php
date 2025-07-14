@props(['menu'])

{{-- Clean functional approach without if/else chains --}}
@php
    // Single source of truth for menu rendering decision
    $shouldRender = menu_has_accessible_content($menu);
    $menuData = $shouldRender ? menu_data($menu) : null;
@endphp

{{-- Only render if accessible - no complex conditionals --}}
@isset($menuData)
    <li class="nav-item">
        @includeWhen($menuData['hasChildren'], 'components.layout.menu-dropdown', $menuData)
        @includeUnless($menuData['hasChildren'], 'components.layout.menu-single', $menuData)
    </li>
@endisset
