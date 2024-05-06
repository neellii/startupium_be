@extends('admin.base')

@section('content')

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row mb-5">
                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 mb-2">
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-block btn-outline-primary">{{ __('Назад') }}</a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    @include('admin.entities.parts.project-card')
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

@endsection
