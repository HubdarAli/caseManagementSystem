@extends('layouts.default')
@section('content')
@section('header_styles')
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/style.css') }}">
@endsection

@section('content')
    <!-- Main Container -->
    <!-- Page Content -->
    <div class="content">
        <!-- Overview -->
        <div class="d-flex flex-column flex-md-row justify-content-md-between text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Dashboard
                </h1>
                <h2 class="h6 fw-medium fw-medium text-muted mb-0">
                    Welcome <b class="fw-semibold text-success" style="cursor: auto">{{ Auth::user()?->name }}</b>,
                    everything looks great.
                </h2>
            </div>
        </div>
    </div>
@endsection
