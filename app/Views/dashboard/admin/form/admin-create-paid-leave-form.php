<form action="/create-paid-leave" method="POST">
    <input type="hidden" name="id" value="<?= $employee->ID ?>">
    <input type="hidden" name="dept_id" value="<?= $employee->DEPARTMENT_ID ?>">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-floating mb-3">
                <input type="text" id="datepicker" class="form-control datepicker"
                id="from_to_date" name="from_to_date" required>
                <label for="datepicker" class="form-label">From - To Date</label>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-floating mb-3">
                <textarea class="form-control" name="reason" id="reason" required></textarea>
                <label for="reason" class="form-label">Reason</label>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="type" id="type" value="Paid Leave|PL"
                readonly disabled>
                <label for="type" class="form-label">Leave Type</label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-sm btn-success">Submit</button>
</form>