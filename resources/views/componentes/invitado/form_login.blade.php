<div class="m-login__signin">
    <div class="m-login__head">
        <h3 class="m-login__title">Ingresar</h3>
    </div>
    <form class="m-login__form m-form" action="{{ route('login') }}" method="post">
        @csrf
        <div class="form-group m-form__group">
            <input class="form-control m-input" type="email" placeholder="Email" name="email" autocomplete="off" required value="{{ old('email') }}" autofocus>
            @if ($errors->has('email'))
                <div class="form-control-feedback text-danger">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>
        <div class="form-group m-form__group">
            <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Contraseña" name="password" required>
            @if ($errors->has('password'))
                <div class="form-control-feedback">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>
        <input type="hidden" name="remember" value="true">
        <div class="row m-login__form-sub">
            <div class="col m--align-right m-login__form-right">
                <a href="{{route('password.request')}}" class="m-link">
                    Olvidó su contraseña?
                </a>
            </div>
        </div>
        <div class="m-login__form-action">
            <button class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn" type="submit">
                Iniciar sesión
            </button>
        </div>
    </form>
</div>