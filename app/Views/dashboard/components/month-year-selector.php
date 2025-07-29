<form method="get" class="d-flex">
    <select name="month" class="form-select form-select-sm" style="width: fit-content;" onchange="this.form.submit()">
        <option value="<?= $month ?>" selected><?= getMonthName($month) ?></option>
        <hr>
        <option value="1">January</option>
        <option value="2">February</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
    </select>
    <select class="form-select form-select-sm" name="year" onchange="this.form.submit()">
        <option value="<?= $year ?>" selected><?= $year ?></option>
        <hr>
        <option value="<?= date('Y', strtotime("-5 years")) ?>"><?= date('Y', strtotime("-5 years")) ?>
        </option>
        <option value="<?= date('Y', strtotime("-4 years")) ?>"><?= date('Y', strtotime("-4 years")) ?>
        </option>
        <option value="<?= date('Y', strtotime("-3 years")) ?>"><?= date('Y', strtotime("-3 years")) ?>
        </option>
        <option value="<?= date('Y', strtotime("-2 years")) ?>"><?= date('Y', strtotime("-2 years")) ?>
        </option>
        <option value="<?= date('Y', strtotime("-1 years")) ?>"><?= date('Y', strtotime("-1 years")) ?>
        </option>
        <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
    </select>
</form>