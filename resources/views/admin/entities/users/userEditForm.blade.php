@extends('admin.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-6 col-md-5 col-lg-4 col-xl-3">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> {{ __('Редактировать') }} {{ $user->firstname }}</div>
                    <div class="card-body">
                        <br>
                        <form method="POST" action="{{route('admin.users.update', $user)}}">
                            @csrf
                            @method('PUT')
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <svg class="c-icon c-icon-sm">
                                          <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                                      </svg>
                                    </span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Имя') }}" name="firstname" value="{{ old('firstname', $user->firstname) }}" required autofocus>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <svg class="c-icon c-icon-sm">
                                          <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                                      </svg>
                                    </span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Фамилия') }}" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="role" class="col-form-label">Роль</label>
                                <select id="role" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="role">
                                    @foreach ($roles as $value => $label)
                                        <option value="{{ $value }}"{{ $value === old('role', $user->role) ? ' selected' : '' }}>{{ $label }}</option>
                                    @endforeach;
                                </select>
                                @if ($errors->has('role'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('role') }}</strong></span>
                                @endif
                            </div>

                            <button class="btn btn-block btn-success" type="submit">{{ __('Обновить') }}</button>
                        </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('javascript')

@endsection
