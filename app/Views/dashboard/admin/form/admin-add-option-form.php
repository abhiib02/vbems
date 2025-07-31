<form action="/add-option" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="option_name" id="option" required>
                <label for="option">Option Name</label>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="option_value" id="option_value" required>
                <label for="option_value">Value</label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success">Add Option</button>
</form>