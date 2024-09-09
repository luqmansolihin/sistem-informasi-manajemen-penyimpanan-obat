@extends('layouts.skeleton')

@section('app')
    @include('partials.navbar')

    @include('partials.sidebar')

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.footer')
@endsection
