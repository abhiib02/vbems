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
                    <p class="text-center fw-bold">Voyagersbeat EMS</p>
                    <p><i class="ri-user-line"></i>Forgot Password</p>

                    <form action="/forgotpassword" id="forgot-password-form" method="post">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="name@example.com" required>
                            <label for="email">Email address</label>
                        </div>
                        <br>
                        <div class="d-flex justify-content-between">
                            <button type="submit" id="forgot-pass-request"
                                class="btn btn-sm btn-success">Submit</button>
                            <a href="/login" class="btn btn-sm btn-outline-secondary">Return to Login</a>
                        </div>

                    </form>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</div>
<script>
    document.querySelector('#forgot-password-form').addEventListener('submit', () => {
        runToast('Sending Reset Link To Mail...', 'info', '10s');
    })
</script>