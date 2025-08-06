<div class="card height-on-mobile">
    <div class="card-header">
        <h5 class="fw-bold m-0">Profile</h5>
        <small>Profile Created on <?= $employee->CREATED_ON ?></small>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <form action="/update-profile-disabled" method="POST">
                    <div class="row">
                        <input type="hidden" name="id" value="<?= $employee->ID ?>">
                        <div class="">
                            <div class="form-floating mb-3">
                                <input type="text" id="name" name="name" class="form-control"
                                value="<?= $employee->NAME ?>" disabled>
                                <label for="name" class="form-label">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" id="email" name="email" class="form-control"
                                value="<?= $employee->EMAIL ?>" disabled>
                                <label for="email" class="form-label">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" id="contact" name="contact" maxlength="10" class="form-control"
                                value="<?= $employee->CONTACT ?>" disabled>
                                <label for="contact" class="form-label">Contact</label>
                            </div>

                        </div>
                    </div>
                    <!--<button type="submit" class="btn btn-success">Update Profile</button>-->
                </form>
            </div>
            <div class="col-md-6 col-lg-4 hide-on-mobile">

                <div class="form-floating mb-3">
                    <?php $roles = [
                        'Employee',
                        'Admin'
                    ]; ?>
                    <input type="text" id="role" name="role" class="form-control" value="<?= $roles[$employee->ROLE] ?>"
                    readonly disabled>
                    <label for="role" class="form-label">Role</label>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="Department" name="" class="form-control"
                            value="<?= $DepartmentName ?>" readonly disabled>
                            <label for="Department" class="form-label">Department</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="name" name="" class="form-control"
                            value="<?= $employee->DESIGNATION ?>" readonly disabled>
                            <label for="designation" class="form-label">Designation</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="Salary" name="" class="form-control" value="<?= $salary ?>"
                    readonly disabled>
                    <label for="Salary" class="form-label">Basic Salary</label>
                </div>
            </div>
        </div>
    </div>

</div>