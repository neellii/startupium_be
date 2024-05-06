@extends('admin.base')

@section('content')

    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row mb-5">
                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 mb-2">
                    <a href="{{ route('admin.comments.index') }}"
                       class="btn btn-block btn-outline-primary">{{ __('Назад') }}</a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    @include('admin.entities.parts.comment-card')
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Все потомки') }}</div>
                        <div class="card-body">
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Текст</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($comment->children as $child)
                                    <tr>
                                        <td>
                                            {{$child->id}}
                                        </td>
                                        <td>
                                            <div>{{$child->comment}}</div>
                                            <div class="small">
                                                Статус: <span
                                                    class="{{$child->isDeleted() ? 'text-danger' : 'text-muted'}}">{{$child->isDeleted() ? 'Удален' : 'Активен'}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{route('admin.comments.show', $child->id)}}"
                                               class="btn btn-primary">Подробнее</a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                            {{ $comment->children->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

@endsection
