{{-- New Page Builder - Phase 0 Temporary --}}
<x-layout.app :title="$title ?? 'Page Builder'" :pakaiSidebar="false" :pakaiTopBar="false">
    <!-- Header -->
    <div class="page-header d-print-none sticky-top bg-white border-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center">
                        <a href="{{ url('panel/pages') }}" class="btn btn-ghost-secondary btn-icon me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <polyline points="15,6 9,12 15,18"/>
                            </svg>
                        </a>
                        <div>
                            <h2 class="page-title mb-0">{{ $title ?? 'Page Builder' }}</h2>
                            <div class="page-subtitle">{{ $description ?? 'Build and design pages with drag & drop components' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="btn-list">
                        <button type="button" class="btn btn-outline-primary" id="preview-page">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                            </svg>
                            Preview
                        </button>
                        <button type="button" class="btn btn-success" id="save-page">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-16a2 2 0 0 1 2 -2"/>
                                <circle cx="12" cy="14" r="2"/>
                                <polyline points="14,4 14,8 8,8 8,4"/>
                            </svg>
                            Save Page
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="page-body p-0">
        <div class="d-flex h-100" style="height: calc(100vh - 64px);">
            <!-- Components Sidebar -->
            <div style="width: 280px; flex-shrink: 0;">
                @include('panel.pages.builder.sidebar')
            </div>

            <!-- Builder Canvas -->
            <div class="flex-fill">
                @include('panel.pages.builder.canvas')
            </div>

            <!-- Property Editor -->
            <div style="width: 320px; flex-shrink: 0;">
                @include('panel.pages.builder.property-editor')
            </div>
        </div>
    </div>

    <!-- Current page data for reference -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        window.pageData = {
            id: {{ $page->id }},
            title: @json($page->title),
            slug: @json($page->slug),
            template: @json($page->template ?? 'builder'),
            meta_title: @json($page->meta_title ?? ''),
            meta_description: @json($page->meta_description ?? ''),
            is_published: {{ $page->is_published ? 'true' : 'false' }},
            updateUrl: @json(route('panel.pages.update', $page->id)),
            previewUrl: @json(route('dynamic.page', $page->slug))
        };
        
        window.csrfToken = '{{ csrf_token() }}';
        
        console.log('Page Builder - Phase 4 Progress: Builder System');
        console.log('✅ Phase 0-3 Complete:');
        console.log('  - Phase 0: Cleanup & Foundation');
        console.log('  - Phase 1: Base Components & Registry');
        console.log('  - Phase 2: Layout Components (Container, Row, Column, Section)');
        console.log('  - Phase 3: Content Components (Heading, Text, Button, Image)');
        console.log('🔄 Phase 4: Builder System (IN PROGRESS):');
        console.log('  ✅ Component Sidebar - Dynamic component loading from registry');
        console.log('  ✅ Canvas System - Drag & drop, component management, responsive preview');
        console.log('  ✅ Property Editor - Universal property editor with validation & presets');
        console.log('  ⏳ Save & Preview System - Backend integration (FINAL STEP)');
        console.log('🎯 Ready for Phase 4.4: Save & Preview System');
    </script>
</x-layout.app>
