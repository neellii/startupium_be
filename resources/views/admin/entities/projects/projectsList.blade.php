@extends('admin.base')

@section('content')

    <div class="container-fluid">

        @include('admin.filters.project-filter')

        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Все проекты') }}</div>
                        <div class="card-body">
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $project->id }}</td>
                                        <td>
                                            <div>{{$project->title}}</div>
                                            <div class="small">
                                                Статус: @include('admin.entities.parts.project-status')
                                            </div>
                                        </td>
                                        <td>{{$project->description}}</td>
                                        <td>
                                            <a href="{{route('admin.projects.show', $project->id)}}" class="btn btn-primary">Подробнее</a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                            {{ $projects->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

@endsection
