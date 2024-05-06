<div class="card">
    <div class="card-header d-flex flex-row {{$project->isDeleted() ? 'border-danger' : ''}}">
        <div class="ml-2">
            <div>{{$project->title}}</div>
            @if($project->isDeleted())
                <div class="small text-danger">
                    <span>Удален: </span>{{$project->deleted_at}}
                </div>
            @else
                <div class="small text-muted">
                    <span>Создан: </span>{{$project->created_at}}
                </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="mt-0">
            <h5 class="card-title">Статус</h5>
            <h6 class="card-subtitle mb-2 text-muted">
                @include('admin.entities.parts.project-status')
            </h6>
        </div>
        <div class="mt-3">
            <h5 class="card-title">Причина отклонения</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{$project->reason ? $project->reason : '...'}}</h6>
        </div>
        <div class="mt-3">
            <h5 class="card-title">Автор</h5>
            <h6 class="card-subtitle mb-2 text-muted">
                <a href="{{route('admin.users.show', $author->id)}}">{{$author->lastname}} {{$author->firstname}}</a>
            </h6>
        </div>
        <div class="mt-3">
            <h5 class="card-title">Название</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{$project->title}}</h6>
        </div>
        <div class="mt-3">
            <h5 class="card-title">Описание</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{$project->description}}</h6>
        </div>
        <div class="mt-3">
            <h5 class="card-title">Текст</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{$project->text}}</h6>
        </div>
        <div class="mt-3">
            <h5 class="card-title">Тэги</h5>
            <h6 class="card-subtitle mb-2 text-muted">
                @foreach($project->tags as $tag)
                    {{$tag->tag}}
                @endforeach
            </h6>
        </div>
        <div class="mt-5">
            @if(!$author->isDeleted())
                @if($project->isDeleted())
                    <form action="{{route('admin.projects.restore', $project->id)}}" method="POST">
                        @method('PUT')
                        @csrf
                        <button class="btn btn-link text-warning">Восстановать</button>
                    </form>
                @else
                    <div class="mt-5 d-flex">
                        <button data-toggle="modal" data-target="#delete-project"
                                class="btn btn-link text-danger">Удалить
                        </button>

                        @if($project->isOnModeration())
                            <form action="{{route('admin.projects.moderate', $project)}}" method="POST">
                                @method('PUT')
                                @csrf
                                <button class="btn btn-link text-success">Модерация</button>
                            </form>
                        @endif
                        @if($project->isOnModeration() || $project->isActive())
                            <button data-toggle="modal" data-target="#reject-project"
                                    class="btn btn-link text-warning">Отклонить
                            </button>
                            <a href="{{config('app.url') . '/project/' . $project->id}}" class="btn btn-link text-primary">См. на сайте</a>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@include('admin.entities.parts.modals.delete-project')
@include('admin.entities.parts.modals.reject-project')
