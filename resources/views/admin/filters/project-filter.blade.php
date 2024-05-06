<div class="card mb-3">
    <div class="card-header">Фильтр / Поиск по проектам</div>
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
                        <label for="title" class="col-form-label">Название</label>
                        <input id="title" class="form-control" name="title"
                               value="{{ request('title') }}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="status" class="col-form-label">Статус</label>
                        <select id="status" class="form-control" name="status">
                            <option value=""></option>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}"{{ $value === request('status') ? ' selected' : '' }}>{{ $label }}</option>
                            @endforeach;
                        </select>
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

