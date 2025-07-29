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
                    <p><i class="ri-user-line"></i>Set A New Password</p>

                    <form action="/reset" method="post">
                        <input type="hidden" name="token" value="<?php echo $token; ?>" />
                        <div class="mb-3">
                            <label class="password">Password</label>
                            <input type="password" name="password" class="form-control" minlength="8" id="password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="cpassword" id="confirm_password" minlength="8"
                                class="form-control" required>
                        </div>
                        <div class="">
                            <label for="" class="text-danger" id="error-msg"></label>
                        </div>
                        <button type="submit" name="btn_login" class="btn btn-success">Reset
                            Password</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</div>
<script>
    var password = document.getElementById("password"),
        confirm_password = document.getElementById("confirm_password"),
        errormsg = document.getElementById("error-msg");

    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
            errormsg.innerHTML = "Passwords Don't Match";
        } else {
            confirm_password.setCustomValidity('');
            errormsg.innerHTML = "";
        }
    }
    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>