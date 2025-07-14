<a class="{{ $linkClasses }}" {!! $collapseAttrs !!}>
    {!! $icon !!}
    {{ menu_text($menu) }}
    {!! menu_chevron() !!}
</a>
<div class="collapse" id="{{ menu_submenu_id($menu) }}">
    <!-- Recursive children will be rendered here by the helper -->
</div>
