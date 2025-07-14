<a class="{{ $linkClasses }}" {!! $collapseAttrs !!}>
    {!! $icon !!}
    {{ menu_text($menu) }}
    {!! menu_chevron() !!}
</a>
<div class="collapse" id="{{ menu_submenu_id($menu) }}">
    <ul class="nav flex-column ms-3">
        @foreach ($accessibleChildren as $child)
            <li class="nav-item">
                <a class="{{ menu_link_classes($child) }}" href="{{ menu_url($child) }}">
                    {!! menu_icon($child) !!}
                    {{ menu_text($child) }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
