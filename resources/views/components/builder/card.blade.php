<div class="builder-component" data-type="card" data-id="{{ $id }}">
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
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Card Title</h3>
        </div>
        <div class="card-body">
            <p class="text-muted mb-0">This is some text within a card body.</p>
        </div>
    </div>
</div>
