<form action="/add-employee" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="name" class="form-control" name="name" id="name" required>
                <label for="name">Employee Name</label>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" id="email" required>
                <label for="email">Employee Email</label>
            </div>
        </div>
        <div class="col-6 col-lg-12">
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="contact" id="contact" required>
                <label for="contact">Employee Contact No</label>
            </div>
        </div>
        <div class="col-6 col-lg-12">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="biometric" id="biometric" required>
                <label for="biometric">Employee Biometric ID</label>
            </div>
        </div>

        <div class="col-6 col-lg-12">
            <div class="form-floating mb-3">
                <select class="form-select" name="department" aria-label="Default select example" required>
                    <option selected disabled value="">Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department->ID ?>"><?= $department->NAME ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="designation">Employee Department</label>
            </div>
        </div>
        <div class="col-6 col-lg-12">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="designation" id="designation" required>
                <label for="designation">Employee Designation</label>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="salary" id="salary" required>
                <label for="salary">Employee Salary</label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success">Add Employee</button>
</form>