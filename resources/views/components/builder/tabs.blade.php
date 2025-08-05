<div class="builder-component" data-type="tabs" data-id="{{ $id }}">
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
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tab-{{ $id }}-1" class="nav-link active" data-bs-toggle="tab">Tab 1</a>
                </li>
                <li class="nav-item">
                    <a href="#tab-{{ $id }}-2" class="nav-link" data-bs-toggle="tab">Tab 2</a>
                </li>
                <li class="nav-item">
                    <a href="#tab-{{ $id }}-3" class="nav-link" data-bs-toggle="tab">Tab 3</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-{{ $id }}-1">
                    <p>Content for Tab 1</p>
                </div>
                <div class="tab-pane" id="tab-{{ $id }}-2">
                    <p>Content for Tab 2</p>
                </div>
                <div class="tab-pane" id="tab-{{ $id }}-3">
                    <p>Content for Tab 3</p>
                </div>
            </div>
        </div>
    </div>
</div>
