@extends('layouts.app')

@section('title', $title ?? 'Public Page')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $title ?? $menu->menu_name }}</div>
                <div class="card-body">
                    @if(isset($content))
                        {!! $content !!}
                    @else
                        <h4>{{ $title ?? $menu->menu_name }}</h4>
                        @if(isset($menu) && $menu->menu_description)
                            <p>{{ $menu->menu_description }}</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
