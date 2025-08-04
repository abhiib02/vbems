<form action="/update-profile" method="POST">
    <input type="hidden" name="id" value="<?= $employee->ID ?>">
    <input type="hidden" name="isAdmin" value="1">
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" value="<?= $employee->NAME ?>"
                    name="name" id="Name" required>
                <label for="Name" class="form-label">Name</label>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" value="<?= $employee->EMAIL ?>" name="email" id="Email" required>
                <label for="Email" class="form-label">Email</label>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-floating mb-3">
                <input type="number" class="form-control" value="<?= $employee->CONTACT ?>"
                    name="contact" id="Contact" required>
                <label for="Contact" class="form-label">Contact No</label>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" value="<?= $employee->BIOMETRIC_ID ?>"
                name="biometric" id="Bio" required>
                <label for="Bio" class="form-label">Biometric ID</label>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" value="<?= $employee->DESIGNATION ?>"
                name="designation" id="Designation" required>
                <label for="Designation" class="form-label">Designation</label>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-floating mb-3">
                <select class="form-select" name="department" aria-label="Default select example"
                required>
                <option selected value="<?= $employee->DEPARTMENT_ID ?>">Current Department : <?= $employee->DEPARTMENT_NAME ?></option>
                <hr>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department->ID ?>"><?= $department->NAME ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="department" class="form-label">Employee Department</label>
                
            </div>
        </div>


    </div>

    <button type="submit" class="btn btn-sm btn-success">Update Profile</button>
</form>