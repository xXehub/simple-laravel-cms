@extends('layouts.tabler')

@section('title', $title ?? 'Dynamic Page')

@section('page-header')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    {{ $title ?? $menu->menu_name }}
                </h2>
                @if(isset($description) && $description)
                <div class="page-subtitle">{{ $description }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title ?? $menu->menu_name }}</h3>
                </div>
                <div class="card-body">
                    @if(isset($content))
                        <div class="mb-3">
                            {!! $content !!}
                        </div>
                    @endif

                    @if(isset($menu) && $menu->menu_description)
                        <div class="mb-3">
                            <p class="text-secondary">{{ $menu->menu_description }}</p>
                        </div>
                    @endif

                    @if(!isset($content) && (!isset($menu) || !$menu->menu_description))
                        <div class="empty">
                            <div class="empty-img">
                                <img src="{{ asset('static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                            </div>
                            <p class="empty-title">Dynamic Page</p>
                            <p class="empty-subtitle text-secondary">
                                This is a dynamically generated page. Content can be configured through the menu management system.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
