@props(['page'])

<div class="btn-list flex-nowrap">
    {{-- Edit/Builder Button --}}
    @can('update-pages')
        @if($page['builder_data'])
            <a href="{{ route('panel.pages.builder', $page['id']) }}" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <rect x="4" y="4" width="16" height="4" rx="1"/>
                    <rect x="4" y="12" width="6" height="8" rx="1"/>
                    <rect x="14" y="12" width="6" height="8" rx="1"/>
                </svg>
                Builder
            </a>
        @else
            <button type="button" class="btn btn-warning btn-sm" onclick="editPage({{ $page['id'] }})">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                    <path d="M16 5l3 3"/>
                </svg>
                Edit
            </button>
        @endif
    @endcan

    {{-- Preview Button --}}
    <a href="{{ route('dynamic.page', $page['slug']) }}" target="_blank" class="btn btn-info btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
        </svg>
        View
    </a>

    {{-- Delete Button --}}
    @can('delete-pages')
        <button type="button" class="btn btn-danger btn-sm" onclick="deletePage({{ $page['id'] }}, '{{ addslashes($page['title']) }}')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                <line x1="4" y1="7" x2="20" y2="7"/>
                <line x1="10" y1="11" x2="10" y2="17"/>
                <line x1="14" y1="11" x2="14" y2="17"/>
                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
            </svg>
            Delete
        </button>
    @endcan
</div>

<script>
    function deletePage(id, title) {
        if (confirm(`Are you sure you want to delete "${title}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('panel/pages') }}/${id}`;
            
            // CSRF Token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);
            
            // Method Override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    function editPage(id) {
        // Simple edit functionality - you can expand this
        window.location.href = `{{ url('panel/pages') }}/${id}/edit`;
    }
</script>
