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
                    <p class="text-center fw-bold">Voyagersbeat Admin Signup</p>
                    <p><i class="ri-user-line"></i>Register</p>

                    <form action="/signupvalidation-admin" class="" method="post">

                        <input type="hidden" name="role" value=1>
                        <div class=" mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control " id="name" name="name" placeholder="Name" required>
                        </div>
                        <div class=" mb-3">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control " id="email" name="email"
                                placeholder="name@example.com" required>
                        </div>
                        <div class=" mb-3">
                            <label for="contact">Contact</label>
                            <input type="text" class="form-control " id="contact" name="contact"
                                placeholder="9876543210" maxlength="10" required>
                        </div>
                        <div class=" mb-3 ">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" minlength="6" id="password" name="password"
                                required>
                            <small class="">( Password Length must be more than 8 Characters )</small>
                        </div>
                        <div class=" mb-3 ">
                            <label for="cpassword">Confirm Password</label>
                            <input type="password" class="form-control" minlength="6" id="cpassword" name="cpassword"
                                required oninput="handlePasswordEvents()">
                        </div>
                        <div id="match" class="hide mb-3">
                        </div>
                        <button type="submit" id="submit" class="btn btn-sm btn-success">Sign up</button>
                        <a href="/login" class="btn btn-sm btn-outline-dark">Login</a>
                    </form>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</div>
<script>
    function handlePasswordEvents() {
        checkcpass();
    }

    function checkcpass() {
        let password = document.querySelector('#password');
        let cpassword = document.querySelector('#cpassword');
        let feedback = document.querySelector('#match');
        let submit = document.querySelector('#submit');
        if (password.value != cpassword.value) {
            feedback.classList.remove('hide');
            feedback.textContent = 'Password is not matching';
            feedback.classList.add('text-danger');
            submit.setAttribute('disabled', '');
        } else {
            feedback.classList.add('hide');
            feedback.textContent = '';
            feedback.classList.add('text-danger');
            submit.removeAttribute('disabled', '');
        }
    }
</script>