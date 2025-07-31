<dialog id="calculatedsalary-attendance" class="col-lg-4 position-relative border-0 rounded shadow p-0">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="align-content-center">
                    <span class="fw-bold fs-5">Calculated Salary of <?= getMonthName($month) ?></span><br>
                    <?php if ($attendedDays < $minimumDayAttendance): ?>
                        <small>( To be eligible for salary, an employee must have attended a minimum of <?= $minimumDayAttendance ?> days in the
                            month. )</small>
                    <?php endif; ?>
                </div>
                <button class=" btn btn-sm btn-outline-secondary" onclick="closeDialog()">X</button>
            </div>
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
                    <td>
                        <ul class="m-0" >
                            <li class="text-success">Full Days <b>(<?= $fullDay ?>)</b></li>
                            <?= ($paid_leaves != 0) ? "<li class='text-success'> Paid Leave Days <b>($paid_leaves)</b></li>" : ''; ?>
                            <li class="text-success">Non Leave Sunday <b>(<?= $coutable_sunday ?>) </b></li>
                            <li class="text-success">Half Day <b>(<?= $halfDay ?> / 2) => (<?= $halfDay / 2 ?>)</b></li>
                            <?= ($zeroCreditLeaveDays != 0 || $zeroCreditLeaveDays != '') ? "<li class='text-danger'> Zero Credit Leave <b>($zeroCreditLeaveDays x 2) =>  ($doubleLeaveDays) </b></li>" : ''; ?>
                            <li>Total Worked Days <b>(<?= $totalWorkingDays ?> Days)</b></li>
                        </ul>


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
        <?php if ($attendedDays < $minimumDayAttendance): ?>
            <small>( To be eligible for salary, an employee must have attended a minimum of <?= $minimumDayAttendance ?> days in the
                month. )</small>
        <?php endif; ?>
    </div>
</div>