<x-layout.app :title="$page->title" :pakaiSidebar="false" :pakaiTopBar="false">
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h1 class="page-title">{{ $page->title }}</h1>
                        @if($page->meta_description)
                            <div class="page-subtitle">{{ $page->meta_description }}</div>
                        @endif
                    </div>
                    @auth
                        @can('update-pages')
                            <div class="col-auto ms-auto d-print-none">
                                <a href="{{ route('dynamic.page', ['slug' => 'panel/pages']) }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                        <path d="M16 5l3 3"/>
                                    </svg>
                                    Edit Page
                                </a>
                            </div>
                        @endcan
                    @endauth
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Contact Header -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            {!! $page->content !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Informasi Kontak</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="datagrid">
                                                    <div class="datagrid-item">
                                                        <div class="datagrid-title">Alamat</div>
                                                        <div class="datagrid-content">
                                                            <address>
                                                                Jl. Contoh No. 123<br>
                                                                Kota, Provinsi 12345
                                                            </address>
                                                        </div>
                                                    </div>
                                                    <div class="datagrid-item">
                                                        <div class="datagrid-title">Telepon</div>
                                                        <div class="datagrid-content">
                                                            <a href="tel:+6221234567">(021) 234-567</a>
                                                        </div>
                                                    </div>
                                                    <div class="datagrid-item">
                                                        <div class="datagrid-title">Email</div>
                                                        <div class="datagrid-content">
                                                            <a href="mailto:info@example.com">info@example.com</a>
                                                        </div>
                                                    </div>
                                                    <div class="datagrid-item">
                                                        <div class="datagrid-title">Jam Operasional</div>
                                                        <div class="datagrid-content">
                                                            Senin - Jumat: 08:00 - 17:00<br>
                                                            Sabtu: 08:00 - 12:00
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Form -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Kirim Pesan</h3>
                                            </div>
                                            <div class="card-body">
                                                <form>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Lengkap</label>
                                                                <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" class="form-control" placeholder="Masukkan email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Subjek</label>
                                                        <input type="text" class="form-control" placeholder="Masukkan subjek">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Pesan</label>
                                                        <textarea class="form-control" rows="6" placeholder="Tulis pesan Anda"></textarea>
                                                    </div>
                                                    <div class="form-footer">
                                                        <button type="submit" class="btn btn-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <line x1="10" y1="14" x2="21" y2="3"/>
                                                                <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5"/>
                                                            </svg>
                                                            Kirim Pesan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer text-muted">
                                <div class="row">
                                    <div class="col">
                                        <div class="d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <rect x="4" y="5" width="16" height="16" rx="2"/>
                                                <line x1="16" y1="3" x2="16" y2="7"/>
                                                <line x1="8" y1="3" x2="8" y2="7"/>
                                                <line x1="4" y1="11" x2="20" y2="11"/>
                                            </svg>
                                            <small>Dibuat: {{ $page->created_at->format('F d, Y') }}</small>
                                            @if($page->updated_at->ne($page->created_at))
                                                <span class="mx-2">•</span>
                                                <small>Diperbarui: {{ $page->updated_at->format('F d, Y') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    @if($page->template)
                                        <div class="col-auto">
                                            <div class="d-flex align-items-center text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18z"/>
                                                    <path d="M12 7l0 5l3 3"/>
                                                </svg>
                                                <small>Template: {{ $page->template }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($page->meta_title || $page->meta_description)
        @push('meta')
            @if($page->meta_title)
                <meta name="title" content="{{ $page->meta_title }}">
                <meta property="og:title" content="{{ $page->meta_title }}">
            @endif
            @if($page->meta_description)
                <meta name="description" content="{{ $page->meta_description }}">
                <meta property="og:description" content="{{ $page->meta_description }}">
            @endif
        @endpush
    @endif
</x-layout.app>
