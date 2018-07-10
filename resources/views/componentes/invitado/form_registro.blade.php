<div class="m-login__signup">
    <div class="m-login__head">
        <h3 class="m-login__title">
            Sign Up
        </h3>
        <div class="m-login__desc">
            Enter your details to create your account:
        </div>
    </div>
    <form class="m-login__form m-form" action="">
        <div class="form-group m-form__group">
            <input class="form-control m-input" type="text" placeholder="Fullname" name="fullname">
        </div>
        <div class="form-group m-form__group">
            <input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off">
        </div>
        <div class="form-group m-form__group">
            <input class="form-control m-input" type="password" placeholder="Password" name="password">
        </div>
        <div class="form-group m-form__group">
            <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Confirm Password" name="rpassword">
        </div>
        <div class="row form-group m-form__group m-login__form-sub">
            <div class="col m--align-left">
                <label class="m-checkbox m-checkbox--light">
                    <input type="checkbox" name="agree">
                    I Agree the
                    <a href="#" class="m-link m-link--focus">
                        terms and conditions
                    </a>
                    .
                    <span></span>
                </label>
                <span class="m-form__help"></span>
            </div>
        </div>
        <div class="m-login__form-action">
            <button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">
                Sign Up
            </button>
            &nbsp;&nbsp;
            <button id="m_login_signup_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">
                Cancel
            </button>
        </div>
    </form>
</div>