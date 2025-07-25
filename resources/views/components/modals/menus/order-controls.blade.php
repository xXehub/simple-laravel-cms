@props(['menu'])

<div class="btn-group" role="group" aria-label="Menu Order Controls">
    <button type="button" class="btn btn-sm btn-outline-primary move-menu-btn" 
            data-menu-id="{{ $menu['id'] }}" data-direction="up" 
            title="Naikan urutan menu" {{ ($menu['is_first'] ?? false) ? 'disabled' : '' }}>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 15l-6-6-6 6" />
        </svg>
    </button>

    <button type="button" class="btn btn-sm btn-outline-primary move-menu-btn" 
            data-menu-id="{{ $menu['id'] }}" data-direction="down" 
            title="Turunkan urutan menu" {{ ($menu['is_last'] ?? false) ? 'disabled' : '' }}>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 9l6 6 6-6" />
        </svg>
    </button>
</div>
