@extends('admin.base')

@section('content')

    <div class="container-fluid">

        @include('admin.filters.user-filter')

        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Все пользователи') }}</div>
                        <div class="card-body">
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th></th>
                                    <th>Имя</th>
                                    <th>Фамилия</th>
                                    <th>E-mail</th>
                                    <th>Роль</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div>{{$user->id}}</div>
                                        </td>
                                        <td class="text-center">
                                            @include('admin.entities.parts.user-avatar')
                                        </td>
                                        <td>
                                            <div>{{$user->firstname}}</div>
                                            @if($user->isDeleted())
                                                <div class="small text-danger">
                                                    <span>Удален: </span>{{$user->deleted_at}}
                                                </div>
                                            @else
                                                <div class="small text-muted">
                                                    <span>Зарегистрирован: </span>{{$user->created_at}}
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $user->lastname }}</td>
                                        <td>
                                            <div>{{$user->email}}</div>
                                            <div class="small text-muted">
                                                <span>Подтвержден: </span>{{$user->email_verified_at ? 'Да' : 'Нет'}}
                                            </div>
                                        </td>
                                        <td>{{ $user->role }}</td>

                                        <td>
                                            <a href="{{route('admin.users.show', $user->id)}}"
                                               class="btn btn-primary">Подробнее</a>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{ $users->links() }}
        </div>
    </div>

@endsection

@section('javascript')

@endsection

