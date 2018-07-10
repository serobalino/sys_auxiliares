<div class="m-login__forget-password show">
    <div class="m-login__head">
        <h3 class="m-login__title">
            Olvido su contraseña?
        </h3>
        <div class="m-login__desc">
            Ingrese su correo electrónico
        </div>
    </div>
    <form class="m-login__form m-form" action="{{ route('password.email') }}" method="post">
        @csrf
        <div class="form-group m-form__group">
            <input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off" required autofocus>
            @if ($errors->has('email'))
                <div class="form-control-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>
        <div class="m-login__form-action">
            <button class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary" type="submit">
                Enviar
            </button>
            &nbsp;&nbsp;
            <a class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom  m-login__btn" href="{{route('login')}}">
                Cancelar
            </a>
        </div>
    </form>
</div>