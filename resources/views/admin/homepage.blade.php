@extends('admin.base')

@section('content')

    <div class="container-fluid">
        <div class="fade-in">
            <!-- /.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">10-ка активных пользователей</div>
                        <!-- /.row--><br>
                        <table class="table table-responsive-sm table-hover table-outline mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center">
                                    <svg class="c-icon">
                                        <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-people"></use>
                                    </svg>
                                </th>
                                <th>Пользователь</th>
                                <th class="text-center">Кол-во комментариев</th>
                                <th class="text-center">Кол-во проектов</th>
                                <th>Активность</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="text-center">
                                        @include('admin.entities.parts.user-avatar')
                                    </td>
                                    <td>
                                        <div><a href="{{route('admin.users.show', $user->id)}}">{{$user->lastname}} {{$user->firstname}}</a></div>
                                        <div class="small text-muted">
                                            <span>Зарегистрирован: </span>{{$user->created_at}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="clearfix">
                                            <div class="float-left"><strong>{{$user->comments_count}}</strong></div>
                                            <div class="float-right"><small class="text-muted">из 100 комментариев</small></div>
                                        </div>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{$user->comments_count}}%"
                                                 aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="clearfix">
                                            <div class="float-left"><strong>{{$user->projects->count()}}</strong></div>
                                            <div class="float-right"><small class="text-muted">из 100 проектов</small></div>
                                        </div>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{$user->projects->count()}}%"
                                                 aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-muted">Последний визит</div>
                                        <strong>{{$user->last_online_at}}</strong>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col-->
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">10-ка популярных проектов</div>
                    <!-- /.row--><br>
                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>
                                <svg class="c-icon">
                                    <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-people"></use>
                                </svg>
                                Автор
                            </th>
                            <th class="text-center">Кол-во комментариев</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td></td>
                                <td>
                                    <div><a href="{{route('admin.projects.show', $project->id)}}">{{$project->title}}</a></div>
                                    <div class="small text-muted">
                                        <span>Создан: </span>{{$project->created_at}}
                                    </div>
                                </td>
                                <td>
                                    {{$project->description}}
                                </td>
                                <td>
                                   <div class="d-flex flex-row">
                                       <div class="c-avatar"><img class="c-avatar-img"
                                                                  src="{{$project->user->getAvatarUrl()}}"
                                                                  alt="{{$project->user->firstname}}">
                                           <span class="c-avatar-status {{$project->user->isOnline() ? 'bg-success' : 'bg-danger'}}"></span>
                                       </div>
                                       <div class="ml-2">
                                           <div>{{$project->user->lastname}} {{$project->user->firstname}}</div>
                                           <div class="small text-muted">
                                               <span>Зарегистрирован: </span>{{$user->created_at}}
                                           </div>
                                       </div>
                                   </div>
                                </td>
                                <td>
                                    <div class="clearfix">
                                        <div class="float-left"><strong>{{$project->comments_count}}</strong></div>
                                        <div class="float-right"><small class="text-muted">из 100 комментариев</small></div>
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$project->comments_count}}%"
                                             aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.col-->
    </div>
        <!-- /.row-->
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
