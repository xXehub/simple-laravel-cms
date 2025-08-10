<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->meta_title ?? $page->title }}</title>
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    
    <!-- Tabler CSS -->
    <link href="{{ asset('libs/tabler/dist/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/tabler/dist/css/tabler-icons.min.css') }}" rel="stylesheet">
</head>
<body>
    @if($page->builder_data)
        <!-- Builder Content -->
        <div class="page-wrapper">
            <div class="page-body">
                {!! $page->renderBuilderContent() !!}
            </div>
        </div>
    @else
        <!-- Template Content -->
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h1 class="card-title">{{ $page->title }}</h1>
                                    @if($page->content)
                                        <div class="card-text">
                                            {!! $page->content !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabler JS -->
    <script src="{{ asset('libs/tabler/dist/js/tabler.min.js') }}"></script>
</body>
</html>
