<form action="/update-salary" method="POST">
    <input type="hidden" name="id" value="<?= $employee->ID ?>">
    <div class="form-floating mb-3">
        <input type="number" class="form-control" value="<?= $employee->BASIC_SALARY ?>"
        name="salary" id="salary" required>
        <label for="salary" class="form-label">Salary</label>
    </div>
    <button type="submit" class="btn btn-sm btn-success">Submit</button>
</form>