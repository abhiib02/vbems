<?php include __DIR__ . '../../../components/salaryCalculationVars.php' ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between flex-column flex-md-column flex-lg-row align-items-center">
                    <div class="mb-2">
                        <h5 class="fw-bold m-0"><?= esc($employeename) ?></h5>
                        <small><?= getMonthName($month) ?> <?= $year ?> Attendance</small>

                    </div>
                    <div>
                        <?= view("dashboard/components/month-year-selector"); ?>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="joiningDate" data-joining="<?= $joiningDate ?>" data-companyname="<?= env('appName') ?>"></div>
                <ul class="d-none">
                    <?php foreach ($Holidays as $holiday): ?>
                        <data class="holidaydate" data-holidaydate="<?= $holiday->DATE ?>" data-holiday="<?= $holiday->HOLIDAY ?>"><?= $holiday->DATE ?></data>
                    <?php endforeach; ?>
                    <?php foreach ($attendance as $entry): ?>
                        <data class="present-date" data-punchin="<?= $entry->CREATED_ON ?>" data-halfday="<?= $entry->HALF_DAY ?>" data-punchout="<?= $entry->UPDATED_AT ?>" data-present='<?= $entry->DATE ?>'><?= $entry->DATE ?></data>
                    <?php endforeach; ?>
                    <?php foreach ($approvedLeaves as $leave): ?>
                        <data class="leave-date" data-leavedate="<?= $leave->FROM_DATE ?>" data-leavetype="<?= $leave->TYPE ?>" data-leavedays='<?= $leave->DAYS ?>' data-leavereason="<?= $leave->REASON ?>"><?= $leave->FROM_DATE ?></data>
                    <?php endforeach; ?>
                </ul>
                <div>
                    <span class="badge green text-dark p-2">Marked Attendance</span>
                    <span class="badge present halfday text-dark p-2">Half Day Marked Attendance</span>
                    <span class="badge Sandwich text-dark p-2">Sandwich Leave</span>
                    <span class="badge leave text-dark p-2">Approved Leave</span>
                    <span class="badge leave text-dark p-2 pl">Paid Leave</span>
                    <span class="badge red text-light p-2">Sundays</span>
                    <span class="badge text-bg-info p-2">Holidays</span>
                    <span class="badge text-bg-light border p-2">No Attendance</span>
                </div>
                <hr>
                <?= view("dashboard/components/calender-employee-attendance"); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="">
            <?= view("dashboard/components/attendance-percentage", $dataArray); ?>
        </div>
        <div class="">
            <?= view("dashboard/components/calculated-salary", $dataArray); ?>
        </div>
        <div class="">
            <?= view("dashboard/components/accumulated-leavecredit"); ?>
        </div>
    </div>
</div>




</div>