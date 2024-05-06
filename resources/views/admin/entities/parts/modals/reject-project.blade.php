<div class="modal fade" id="reject-project" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.projects.reject', $project)}}">
                @method('PUT')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Отклонить проект</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message">Введите причину отклонения</label>
                        <textarea required class="form-control" name="message" rows="3">{{ $project->reason }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">
                        Отмена
                    </button>
                    <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">
                        Отклонить
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

