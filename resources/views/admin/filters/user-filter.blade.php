<div class="card mb-3">
    <div class="card-header">Фильтр / Поиск</div>
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
                        <label for="firstname" class="col-form-label">Имя</label>
                        <input id="firstname" class="form-control" name="firstname"
                               value="{{ request('firstname') }}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="lastname" class="col-form-label">Фамилия</label>
                        <input id="lastname" class="form-control" name="lastname"
                               value="{{ request('lastname') }}">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="email" class="col-form-label">Почта</label>
                        <input id="email" class="form-control" name="email" value="{{ request('email') }}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="role" class="col-form-label">Роль</label>
                        <select id="role" class="form-control" name="role">
                            <option value=""></option>
                            @foreach ($roles as $value => $label)
                                <option
                                    value="{{ $value }}"{{ $value === request('role') ? ' selected' : '' }}>{{ $label }}</option>
                            @endforeach;
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="col-form-label">&nbsp;</label><br/>
                        <button type="submit" class="btn btn-primary">Поиск</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
