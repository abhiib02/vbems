<form action="/add-holiday" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="holiday" id="holiday" required>
                <label for="holiday">Holiday Name</label>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="date" class="form-control" name="date" id="date" required>
                <label for="date">Holiday Date</label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success">Add Holiday</button>
</form>