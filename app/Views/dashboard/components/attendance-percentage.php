<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div class="align-content-center">
                <span class="fw-bold">Attendance Percentage of <?= getMonthName($month) ?></span>
                <small>(Attended <?= $attendedDays ?> Days)</small>
            </div>
            <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                data-bs-title="(<?= $attendedDays ?> Days) / <?= $ExactWorkingDays ?> Days x 100">?</a>
        </div>
    </div>
    <div class="card-body">
        <div class="fs-3 d-flex justify-content-between">
            <b>Percentage</b>
            <b><?= $attendancePercentage ?>%</b>
        </div>
        <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="<?= $attendancePercentage ?>" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar bg-success" style="width: <?= $attendancePercentage ?>%"><?= $attendancePercentage ?>%</div>
        </div>
    </div>
</div>