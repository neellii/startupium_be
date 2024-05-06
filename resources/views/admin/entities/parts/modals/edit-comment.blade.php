<div class="modal fade" id="edit-comment" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.comment.update', $comment)}}">
                @method('PUT')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Редактирование комментария</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message">Введите комментарий</label>
                        <textarea required class="form-control" name="message" rows="3">{{ $comment->comment }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">
                        Отмена
                    </button>
                    <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">
                        Обновить
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
