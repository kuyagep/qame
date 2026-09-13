@extends('app.icts.layouts.app')
@section('title', 'Dashboard')
@section('content-header', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4>
                            Welcome back, <b>{{ auth()->user()->name }}!</b>
                        </h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-regular fa-calendar"></i>
                            <span id="currentSystemTime"></span>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
