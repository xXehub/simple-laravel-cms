<x-layout.app>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Visit Tracking Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('panel.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Visit Tracking</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Database Visits</span>
                            <h4 class="mb-3">{{ number_format($stats['total_database_visits']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="mdi mdi-chart-line text-primary font-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Menu</span>
                            <h4 class="mb-3">{{ number_format($stats['total_menus']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="mdi mdi-menu text-info font-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Buffer Pending</span>
                            <h4 class="mb-3">{{ number_format($stats['buffer_count']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="mdi mdi-database text-warning font-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Redis Status</span>
                            <h4 class="mb-3">
                                @if($stats['redis_available'])
                                    <span class="badge bg-success">Online</span>
                                @else
                                    <span class="badge bg-danger">Offline</span>
                                @endif
                            </h4>
                            <small class="text-muted">{{ $stats['redis_keys'] }} keys</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="mdi mdi-redis text-{{ $stats['redis_available'] ? 'success' : 'danger' }} font-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Actions -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Pengaturan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.visit-tracking.update-retention') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="retention_days" class="form-label">Data Retention (Hari)</label>
                            <input type="number" class="form-control" id="retention_days" 
                                   name="retention_days" value="{{ $retentionDays }}" min="1" max="30">
                            <div class="form-text">Berapa lama data disimpan di Redis (1-30 hari)</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Setting</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('panel.visit-tracking.force-sync') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">
                                <i class="mdi mdi-sync me-1"></i> Force Sync Buffer
                            </button>
                        </form>

                        <form action="{{ route('panel.visit-tracking.auto-cleanup') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="mdi mdi-broom me-1"></i> Auto Cleanup
                            </button>
                        </form>

                        <form action="{{ route('panel.visit-tracking.reset-cache') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="mdi mdi-cached me-1"></i> Reset Cache
                            </button>
                        </form>

                        <a href="{{ route('panel.visit-tracking.export') }}" class="btn btn-success w-100">
                            <i class="mdi mdi-download me-1"></i> Export Data
                        </a>

                        <form action="{{ route('panel.visit-tracking.manual-reset') }}" method="POST" 
                              style="display: inline;" 
                              onsubmit="return confirm('PERINGATAN: Ini akan menghapus SEMUA data tracking! Yakin?')">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="mdi mdi-delete-sweep me-1"></i> Manual Reset (BAHAYA!)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buffer Data -->
    @if(!empty($bufferData))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Buffer Data (Pending Sync)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Slug</th>
                                    <th>Pending Visits</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bufferData as $slug => $count)
                                <tr>
                                    <td>{{ $slug }}</td>
                                    <td><span class="badge bg-primary">{{ $count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Info -->
    <div class="row">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h4 class="header-title text-white mb-0">
                        <i class="mdi mdi-information me-1"></i> Tentang Predis vs Redis
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Predis (Yang Digunakan)</h5>
                            <ul class="list-unstyled">
                                <li><i class="mdi mdi-check text-success me-1"></i> PHP Redis Client</li>
                                <li><i class="mdi mdi-check text-success me-1"></i> Setup mudah</li>
                                <li><i class="mdi mdi-check text-success me-1"></i> Kompatibilitas tinggi</li>
                                <li><i class="mdi mdi-check text-success me-1"></i> Tidak perlu extension</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Redis Server</h5>
                            <ul class="list-unstyled">
                                <li><i class="mdi mdi-information text-info me-1"></i> Database in-memory</li>
                                <li><i class="mdi mdi-information text-info me-1"></i> Kecepatan tinggi</li>
                                <li><i class="mdi mdi-information text-info me-1"></i> Data struktur kaya</li>
                                <li><i class="mdi mdi-information text-info me-1"></i> Persistent storage</li>
                            </ul>
                        </div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <strong>Kesimpulan:</strong> Predis adalah client PHP untuk berkomunikasi dengan Redis server. 
                        Kode aplikasi tetap sama, hanya cara koneksi yang berbeda.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout.app>

@push('scripts')
<script>
    // Simple notification for successful actions
    @if(session('success'))
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);
    @endif

    @if(session('error'))
        setTimeout(function() {
            $('.alert-danger').fadeOut('slow');
        }, 5000);
    @endif
</script>
@endpush
