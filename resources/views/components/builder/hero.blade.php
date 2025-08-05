<div class="builder-component" data-type="hero" data-id="{{ $id }}">
    <div class="component-toolbar">
        <button class="btn btn-sm text-white" onclick="editComponent('{{ $id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
        </button>
        <button class="btn btn-sm text-white" onclick="deleteComponent('{{ $id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3,6 5,6 21,6"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
        </button>
    </div>
    <div class="hero bg-primary text-white">
        <div class="hero-body py-5">
            <div class="container text-center">
                <h1 class="hero-title display-4 fw-bold">Hero Title</h1>
                <p class="hero-subtitle fs-4 mb-4">This is a hero section with centered content and call-to-action buttons.</p>
                <div class="mt-4">
                    <a href="#" class="btn btn-light btn-lg me-3">Primary Action</a>
                    <a href="#" class="btn btn-outline-light btn-lg">Secondary Action</a>
                </div>
            </div>
        </div>
    </div>
</div>
