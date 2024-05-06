@extends('admin.base')

@section('content')

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row mb-5">
                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 mb-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-block btn-outline-primary">{{ __('Назад') }}</a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    @include('admin.entities.parts.user-card')
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            Проекты {{$user->firstname}}
                        </div>
                        <div class="card-body bg-gray-400">
                            <div class="row">
                                @foreach($user->projects as $project)
                                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                        <div class="card {{$project->isDeleted() ? 'border-danger' : 'border-success'}}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{$project->title}}</h5>
                                                <h6 class="card-subtitle mb-2 text-muted">{{$project->description}}</h6>
                                                <a href="{{route('admin.projects.show', $project->id)}}" class="card-link">Показать</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{$user->projects->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

@endsection
