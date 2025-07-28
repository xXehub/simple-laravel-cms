@props([
    'searchPlaceholder' => 'Search...',
    'showBulkDelete' => false,
    'bulkDeletePermission' => null,
    'bulkDeleteText' => 'Hapus Terpilih',
    'bulkDeleteTarget' => '#deleteSelectedModal',
    'recordOptions' => [10, 25, 50, 100],
    'defaultRecords' => 15,
    'recordText' => 'records'
])

<div class="card-header">
    <div class="row w-full">
        <div class="col">
            <div class="dropdown">
                <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                    <span id="page-count" class="me-1">{{ $defaultRecords }}</span>
                    <span>{{ $recordText }}</span>
                </a>
                <div class="dropdown-menu">
                    @foreach ($recordOptions as $option)
                        <a class="dropdown-item" onclick="setPageListItems(event)" data-value="{{ $option }}">
                            {{ $option }} {{ $recordText === 'records' ? 'records' : 'data' }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-auto col-sm-12">
            <div class="ms-auto d-flex flex-wrap btn-list">
                <div class="input-group input-group-flat w-auto">
                    <span class="input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                            stroke-linejoin="round" class="icon icon-1">
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                    </span>
                    <input id="advanced-table-search" type="text" class="form-control" 
                        placeholder="{{ $searchPlaceholder }}" autocomplete="off" />
                    <span class="input-group-text">
                        <kbd>ctrl + F</kbd>
                    </span>
                </div>
                {{ $slot ?? '' }}
                @if ($showBulkDelete && $bulkDeletePermission)
                    @can($bulkDeletePermission)
                        <button type="button" id="delete-selected-btn" class="btn btn-danger" 
                            data-bs-toggle="modal" data-bs-target="{{ $bulkDeleteTarget }}" disabled>
                            {{ $bulkDeleteText }} (<span id="selected-count">0</span>)
                        </button>
                    @endcan
                @endif
            </div>
        </div>
    </div>
</div>
