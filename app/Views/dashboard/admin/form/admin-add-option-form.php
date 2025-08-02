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
                <div class="form-control">
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="option_type" id="type_bool" value="0" checked required onclick="changeInputtype()">
                            <label class="form-check-label h-100 align-content-center" for="type_bool">
                                Boolean
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="option_type" id="type_string" value="1" required onclick="changeInputtype()">
                            <label class="form-check-label h-100 align-content-center" for="type_string">
                                String
                            </label>
                        </div>
                    </div>
                </div>
                <label for="option">Option Type</label>
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

<script>
    function changeInputtype() {
        let checkedValue = parseInt(document.querySelector('.form-check-input:checked').value);
        let valueInput = document.getElementById('option_value');

        valueInput.type = (checkedValue === 1) ? 'text' : 'number';
        valueInput.max = (checkedValue === 0) ? '1' : '';
        valueInput.min = (checkedValue === 0) ? '0' : '';
    }
    changeInputtype();
</script>