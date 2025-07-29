<div class="custom-bg" style="--url:url('/assets/images/bg-ems.jpg');">
    <div class="container">
        <div class="row vh100">
            <div class="col-lg-4"></div>

            <div class="col-lg-4 align-content-center">
                <div class="card p-4">
                    <div class="text-center">
                        <img src="<?= env('appLogoPath') ?>" alt="logo" width="80px">
                    </div>
                    <br>
                    <p class="text-center fw-bold"><?= env('appName') ?> Employee Login</p>
                    <p><i class="ri-user-line"></i> Log in</p>

                    <form action="/login-validation" method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" id="floatingInput"
                                placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="password" id="floatingPassword"
                                placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>
                        <br>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-sm btn-success">Login</button>
                            <a href="/forgot_password_form" class="btn btn-sm btn-outline-secondary">Forgot Password ?</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</div>