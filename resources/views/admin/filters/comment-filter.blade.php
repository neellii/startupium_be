<div class="card mb-3">
    <div class="card-header">Фильтр / Поиск по комментариям</div>
    <div class="card-body">
        <form action="?" method="GET">
            <div class="row">
                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="id" class="col-form-label">ID</label>
                        <input id="id" class="form-control" name="id" value="{{ request('id') }}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="title" class="col-form-label">ID автора</label>
                        <input id="title" class="form-control" name="authorId"
                               value="{{ request('authorId') }}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="title" class="col-form-label">ID проекта</label>
                        <input id="title" class="form-control" name="projectId"
                               value="{{ request('projectId') }}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="col-form-label">&nbsp;</label><br/>
                        <button type="submit" class="btn btn-primary">Искать</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
