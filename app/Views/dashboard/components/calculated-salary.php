<dialog id="calculatedsalary-attendance" class="col-lg-4 position-relative border-0 rounded shadow p-0">
    <div class="card">
        <div class="card-header">
            <h5>
                <span class="fw-bold">Calculated Salary of <?= getMonthName($month) ?></span>
            </h5>
            <small>( To be eligible for salary, an employee must have attended a minimum of <?= $minimumDayAttendance ?> days in the
                month. )</small>
            <button class="position-absolute btn btn-sm btn-outline-secondary" style="top:10px; right:10px;"
                onclick="closeDialog()">X</button>
        </div>
        <div class="card-body">

            <table class="table">
                <tr>
                    <td>Salary <b>(₹<?= $monthsalary ?>)</b> / Total Days
                        <b>(<?= $totalDays_salaryCalculation ?>)</b> = Pay/day <b>(₹<?= $DayPay ?>)</b>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>( Full Days <b>(<?= $fullDay ?>)</b><?= ($paid_leaves != 0) ? "+ Paid Leave Days <b>($paid_leaves)</b>" : ''; ?> +
                        Non Leave Sunday <b>(<?= $coutable_sunday ?>) </b> + Half Day <b>(<?= $halfDay ?> / 2) </b> )  <?= ($zeroCreditLeaveDays != 0 || $zeroCreditLeaveDays != '') ? "- ( Zero Credit Leave <b>($zeroCreditLeaveDays x 2)</b>)" : ''; ?>=
                        <b>(<?= $totalWorkingDays ?> Days)</b>
                    </td>
                </tr>
                <tr>
                    <td>Calculated Salary = <b>₹<?= $calculatedSalary ?>
                            <?= ($attendedDays < $minimumDayAttendance) ? '(15 Days Attendance Required)' : '' ?></b>
                        <?php if (($attendedDays > $minimumDayAttendance)): ?> | <b>(<?= $totalWorkingDays ?> Days)</b> x
                            <b>(<?= $DayPay ?> Pay/day)</b><?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</dialog>


<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div class="align-content-center">
                <span class="fw-bold">Calculated Salary of <?= getMonthName($month) ?></span><br>

            </div>
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" data-bs-title="See Calculation"
                onclick="openDialogModal('calculatedsalary-attendance');">?</button>
        </div>
    </div>
    <div class="card-body">
        <div class="fs-3"><b>₹ <?= $calculatedSalary ?></b></div>
        <small>( To be eligible for salary, an employee must have attended a minimum of <?= $minimumDayAttendance ?> days in the
            month. )</small>
    </div>
</div>