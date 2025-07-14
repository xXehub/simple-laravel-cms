@props(['menus'])

<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <x-layout.sidebar-menus :menus="$menus" />
        </ul>
    </div>
</nav>
