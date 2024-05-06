<div class="card">
    <div class="card-header d-flex flex-row {{$user->isDeleted() ? 'border-danger' : ''}}">
        @include('admin.entities.parts.user-avatar')
        <div class="ml-5">
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
        </div>
    </div>
    <div class="card-body">
        <div class="mt-0">
            <h5 class="card-title">Ф. И.</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{$user->lastname}} {{$user->firstname}}</h6>
        </div>
        <div class="mt-3">
            <h5 class="card-title">О пользователе</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{$user->bio}}</h6>
        </div>
        <div class="mt-3">
            <h5 class="card-title">Роль</h5>
            @if($user->isAdmin())
                <h6 class="card-subtitle mb-2 text-danger">Администратор</h6>
            @elseif($user->isModerator())
                <h6 class="card-subtitle mb-2 text-primary">Модератор</h6>
            @else
                <h6 class="card-subtitle mb-2 text-muted">Пользователь</h6>
            @endif

        </div>
        <div class="mt-3">
            <h5 class="card-title">E-mail</h5>
            <h6 class="card-subtitle mb-2 text-muted">
                <span>{{$user->email}}</span> -
                <span
                    class="{{$user->isEmailVerified() ? 'text-success' : 'text-danger'}}">{{$user->isEmailVerified() ? ' подтвержден' : 'не подтвержден'}}</span>
            </h6>
        </div>
        @if(!$user->isDeleted())
            <div class="mt-5 d-flex">
                <a href="{{route('admin.users.edit', $user)}}" class="btn btn-link text-primary">Редактировать</a>
                <button data-toggle="modal" data-target="#delete-user"
                        class="btn btn-link text-danger">Удалить
                </button>
                @if(!$user->isEmailVerified())
                    <form action="{{ route('admin.users.emailVerified', $user) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <button class="btn btn-link text-success">Подтвердить E-mail</button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</div>

@include('admin.entities.parts.modals.delete-user')
