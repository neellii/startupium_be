@extends('admin.base')

@section('content')

    <div class="container-fluid">

        @include('admin.filters.comment-filter')

        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Все комментарии') }}</div>
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

                                @foreach ($comments as $comment)
                                    <tr>
                                        <td>
                                            {{$comment->id}}
                                        </td>
                                        <td>
                                            <div>{{$comment->comment}}</div>
                                            <div class="small">
                                                Статус: <span class="{{$comment->isDeleted() ? 'text-danger' : 'text-muted'}}">{{$comment->isDeleted() ? 'Удален' : 'Активен'}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{route('admin.comments.show', $comment->id)}}" class="btn btn-primary">Подробнее</a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                            {{ $comments->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

@endsection
