<form action="/add-department" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="department_name" id="Department" required>
                <label for="Department">Department Name</label>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="leave_person_count" id="lpc" value="1">
                <label for="lpc">How Many Employees Can Take Leave @ Same time ?</label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success">Add Department</button>
</form>