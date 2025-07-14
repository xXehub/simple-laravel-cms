@props(['menu'])

@php
    $menuData = menu_data($menu);
@endphp

@unless (!$menuData['shouldRender'])
    <li class="nav-item">
        @includeWhen($menuData['hasChildren'], 'components.layout.menu-dropdown', $menuData)
        @includeUnless($menuData['hasChildren'], 'components.layout.menu-single', $menuData)
    </li>
@endunless
