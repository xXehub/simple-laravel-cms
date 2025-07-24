@props(['menu', 'level' => 0])

@php
    $hasChildren = $menu->hasAccessibleChildren();
    $isActive = $menu->isActive();
    $isNested = $level > 0;

    // Use dynamic route helper - try route name first, then URL
    $menuUrl = $menu->getRouteName() ? menu_route($menu->getRouteName()) : $menu->getUrl();
@endphp

@if ($menu->canAccess())
    @if ($isNested)
        {{-- Nested menu item --}}
        @if ($hasChildren)
            <li class="dropdown dropend">
                <a class="dropdown-item dropdown-toggle {{ $isActive ? 'active' : '' }}"
                    href="#navbar-submenu-{{ $menu->id }}-level-{{ $level }}" data-bs-toggle="dropdown"
                    data-bs-auto-close="false" role="button" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                    {{ $menu->nama_menu }}
                </a>
                <ul class="dropdown-menu">
                    @foreach ($menu->getAccessibleChildren() as $child)
                        <x-layout.sidebar-menu :menu="$child" :level="$level + 1" />
                    @endforeach
                </ul>
            </li>
        @else
            <li>
                <a class="dropdown-item {{ $isActive ? 'active' : '' }}" href="{{ $menuUrl }}">
                    {{ $menu->nama_menu }}
                </a>
            </li>
        @endif
    @else
        {{-- Root level menu item --}}
        <li class="nav-item {{ $hasChildren ? 'dropdown' : '' }} {{ $isActive ? 'active' : '' }}">
            @if ($hasChildren)
                {{-- Root dropdown menu with children --}}
                <a class="nav-link dropdown-toggle {{ $isActive ? 'active' : '' }}"
                    href="#navbar-menu-{{ $menu->id }}" data-bs-toggle="dropdown" data-bs-auto-close="false"
                    role="button" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                    @if ($menu->icon)
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="{{ $menu->icon }}"></i>
                        </span>
                    @endif
                    <span class="nav-link-title">{{ $menu->nama_menu }}</span>
                </a>

                <ul class="dropdown-menu {{ $isActive ? 'show' : '' }}">
                    @foreach ($menu->getAccessibleChildren() as $child)
                        <x-layout.sidebar-menu :menu="$child" :level="1" />
                    @endforeach
                </ul>
            @else
                {{-- Single root menu item --}}
                <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $menuUrl }}">
                    @if ($menu->icon)
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="{{ $menu->icon }}"></i>
                        </span>
                    @endif
                    <span class="nav-link-title">{{ $menu->nama_menu }}</span>
                </a>
            @endif
        </li>
    @endif
@endif

<style>
    /* Tabler.io chevron animation - using built-in pseudo-element */
    .dropdown-toggle::after {
        transition: transform 0.15s ease-in-out;
        transform-origin: center;
    }

    .nav-link.dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(135deg);
    }

    .dropend .dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(135deg);
    }
</style>
