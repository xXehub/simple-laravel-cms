@props(['menu', 'level' => 0])

@php
    $hasChildren = $menu->hasAccessibleChildren();
    $isActive = $menu->isActive();
    $isNested = $level > 0;
@endphp

@if ($menu->canAccess())
    @if ($isNested)
        {{-- Nested menu item --}}
        @if ($hasChildren)
            <div class="dropend">
                <a class="dropdown-item dropdown-toggle {{ $isActive ? 'active' : '' }}"
                    href="#navbar-submenu-{{ $menu->id }}-level-{{ $level }}" data-bs-toggle="dropdown"
                    data-bs-auto-close="false" role="button" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                    {{ $menu->nama_menu }}
                </a>
                <div class="dropdown-menu">
                    @foreach ($menu->getAccessibleChildren() as $child)
                        <x-.layout.sidebar-menu :menu="$child" :level="$level + 1" />
                    @endforeach
                </div>
            </div>
        @else
            <a class="dropdown-item {{ $isActive ? 'active' : '' }}" href="{{ $menu->getUrl() }}">
                {{ $menu->nama_menu }}
            </a>
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

                <div class="dropdown-menu {{ $isActive ? 'show' : '' }}">
                    @foreach ($menu->getAccessibleChildren() as $child)
                        <x-sidebar-menu :menu="$child" :level="1" />
                    @endforeach
                </div>
            @else
                {{-- Single root menu item --}}
                <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $menu->getUrl() }}">
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
