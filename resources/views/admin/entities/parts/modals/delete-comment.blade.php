<div class="modal fade" id="delete-comment" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Вы уверены, что хотите удалить комментарий?</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <div class="d-flex flex-row mb-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase mr-2"
                            data-dismiss="modal">
                        Отмена
                    </button>
                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="mr-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger text-uppercase">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
