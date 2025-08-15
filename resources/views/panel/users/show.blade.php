@props(['user', 'menu' => null])

<x-layout.app title="Detail User" :pakaiSidebar="true" :pakaiTopBar="false">
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            Management
                        </div>
                        <h2 class="page-title">
                            Detail User
                        </h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ url('panel/users') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left"></i>
                                Kembali
                            </a>
                            @if ($user->trashed())
                                @can('delete-users')
                                    <button type="button" class="btn btn-success"
                                        onclick="confirmRestoreUser({{ $user->id }}, '{{ $user->name }}')">
                                        <i class="ti ti-refresh"></i>
                                        Pulihkan
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmForceDeleteUser({{ $user->id }}, '{{ $user->name }}')">
                                        <i class="ti ti-trash"></i>
                                        Hapus Permanen
                                    </button>
                                @endcan
                            @else
                                @can('update-users')
                                    <a href="{{ url('panel/users/' . $user->id . '/edit') }}" class="btn btn-warning">
                                        <i class="ti ti-edit"></i>
                                        Edit
                                    </a>
                                @endcan
                                @can('delete-users')
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}')">
                                        <i class="ti ti-trash"></i>
                                        Hapus
                                    </button>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Informasi User</h3>
                                @if ($user->trashed())
                                    <div class="card-actions">
                                        <span class="badge bg-red">Terhapus</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Avatar</div>
                                        <div class="datagrid-content">
                                            <div class="d-flex align-items-center">
                                                @if ($user->avatar)
                                                    <span class="avatar avatar-sm me-2"
                                                        style="background-image: url({{ asset('storage/' . $user->avatar) }})"></span>
                                                @else
                                                    <span
                                                        class="avatar avatar-sm me-2 bg-primary">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                @endif
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Nama Lengkap</div>
                                        <div class="datagrid-content">{{ $user->name }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Email</div>
                                        <div class="datagrid-content">{{ $user->email }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Status Email</div>
                                        <div class="datagrid-content">
                                            @if ($user->email_verified_at)
                                                <span class="status status-green">Terverifikasi</span>
                                            @else
                                                <span class="status status-yellow">Belum Terverifikasi</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Role</div>
                                        <div class="datagrid-content">
                                            @if ($user->roles->isNotEmpty())
                                                @foreach ($user->roles as $role)
                                                    <span class="badge bg-blue me-1">{{ $role->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Belum ada role</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Status User</div>
                                        <div class="datagrid-content">
                                            @if ($user->trashed())
                                                <span class="status status-red">Terhapus</span>
                                            @else
                                                <span class="status status-green">Aktif</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tanggal Dibuat</div>
                                        <div class="datagrid-content">{{ $user->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Terakhir Diupdate</div>
                                        <div class="datagrid-content">{{ $user->updated_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    @if ($user->trashed())
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Tanggal Dihapus</div>
                                            <div class="datagrid-content">
                                                <span
                                                    class="text-red">{{ $user->deleted_at->format('d M Y, H:i') }}</span>
                                            </div>
                                        </div>
                                        @if ($user->deletedBy)
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Dihapus Oleh</div>
                                                <div class="datagrid-content">
                                                    <div class="d-flex align-items-center">
                                                        @if ($user->deletedBy->avatar)
                                                            <span class="avatar avatar-xs me-2"
                                                                style="background-image: url({{ asset('storage/' . $user->deletedBy->avatar) }})"></span>
                                                        @else
                                                            <span
                                                                class="avatar avatar-xs me-2 bg-secondary">{{ strtoupper(substr($user->deletedBy->name, 0, 2)) }}</span>
                                                        @endif
                                                        {{ $user->deletedBy->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">ID User</div>
                                        <div class="datagrid-content">
                                            <code>{{ $user->id }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($user->roles->isNotEmpty())
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detail Role & Permissions</h3>
                                </div>
                                <div class="card-body">
                                    <div class="datagrid">
                                        @foreach ($user->roles as $role)
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">{{ $role->name }}</div>
                                                <div class="datagrid-content">
                                                    @if ($role->permissions->isNotEmpty())
                                                        @foreach ($role->permissions as $permission)
                                                            <span
                                                                class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Belum ada permission</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Total Permissions</div>
                                            <div class="datagrid-content">
                                                <strong>{{ $user->roles->sum(function ($role) {return $role->permissions->count();}) }}
                                                    Permission(s)</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Include action modals -->
    <x-modals.users.action :user="$user" />
    {{-- <x-alert.confirmation-modal /> --}}
</x-layout.app>
