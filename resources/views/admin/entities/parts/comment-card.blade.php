<div class="card">
    <div class="card-header d-flex flex-row {{$comment->isDeleted() ? 'border-danger' : ''}}">
        <div class="ml-5">
            <h5 class="card-title">Комментарий c ID - {{$comment->id}}</h5>
            <div class="card-subtitle d-flex flex-row">{{$comment->comment}}</div>
        </div>
    </div>
    <div class="card-body">
        <div class="mt-0">
            <h5 class="card-title">Статус</h5>
            @if($comment->isDeleted())
                <div class="card-subtitle mb-2 text-danger">
                    <span>Удален: </span>{{$comment->deleted_at}}
                </div>
            @else
                <div class="card-subtitle mb-2 text-muted">
                    <span>Создан: </span>{{$comment->created_at}}
                </div>
            @endif
        </div>
        <div class="mt-3">
            <h5 class="card-title">Автор</h5>
            <div class="card-subtitle d-flex flex-row">
                <div class="c-avatar"><img class="c-avatar-img"
                                           src="{{$comment->author->getAvatarUrl()}}"
                                           alt="{{$comment->author->firstname}}">
                    <span class="c-avatar-status {{$comment->author->isOnline() ? 'bg-success' : 'bg-danger'}}"></span>
                </div>
                <div class="ml-2">
                    <div>
                        <a href="{{route('admin.users.show', $comment->author->id)}}">{{$comment->author->lastname}} {{$comment->author->firstname}}</a>
                    </div>
                    @if($comment->author->isDeleted())
                        <div class="small text-danger">
                            <span>Удален: </span>{{$comment->author->deleted_at}}
                        </div>
                    @else
                        <div class="small text-muted">
                            <span>Зарегистрирован: </span>{{$comment->author->created_at}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-3">
            <h5 class="card-title">К проекту - <a
                    href="{{route('admin.projects.show', $comment->project->id)}}">{{$comment->project->title}}</a></h5>
            <div class="card-subtitle d-flex flex-row">
                <h6 class="card-subtitle mb-2 text-muted">
                    В статусе: @include('admin.entities.parts.project-status', ['project' => $comment->project])
                </h6>
            </div>
        </div>
        <div class="mt-3">
            <h5 class="card-title">
                @if($comment->parent)
                    <a href="{{route('admin.comments.show', $comment->parent->id)}}">Родитель</a>
                @else
                    Родитель
                @endif
            </h5>
            <div class="card-subtitle d-flex flex-row">
                @if($comment->parent)
                    {{$comment->parent->comment}}
                @else
                    Отстутствует
                @endif
            </div>
        </div>
    </div>
    @if(!$comment->author->isDeleted() && !$comment->project->isDeleted())
        <div class="mt-5 d-flex mb-3">
            @if(!$comment->isDeleted())
                <button data-toggle="modal" data-target="#edit-comment"
                        class="btn btn-link text-primary">Редактировать
                </button>
                <button data-toggle="modal" data-target="#delete-comment"
                        class="btn btn-link text-danger">Удалить
                </button>
            @endif
        </div>
    @endif
</div>


@include('admin.entities.parts.modals.delete-comment')
@include('admin.entities.parts.modals.edit-comment')
