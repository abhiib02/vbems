<div class="row">
    <div class="col-md-6 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-bold m-0">Leave Request Form</h5>
            </div>
            <div class="card-body">
                <form action="/requestleaveprocess" id="leave-request-form" method="POST">
                    <div class="row">
                        <input type="hidden" name="user_id" value="<?= $id ?>">
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="from_to_date" class="form-label">From Date / To Date</label>
                                <input type="text" id="datepicker" required class="form-control" id="from_to_date"
                                    name="from_to_date">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="type" class="form-label">Leave Type</label>
                                <select class="form-select" name="type" id="type" required>
                                    <option selected disabled value>Select Leave Type</option>
                                    <option value="Casual Leave|CL">Casual Leave</option>
                                    <option value="Sick Leave|SL">Sick Leave</option>
                                    <option value="Exam Leave|EL">Exam Leave</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason (Explaination)</label>
                                <textarea class="form-control" name="reason" id="reason" rows="6" required></textarea>
                            </div>
                        </div>
                    </div>
                    <?php if ($leavecredit > 1): ?>
                        <button type="submit" class="btn btn-warning">Submit Request</button>
                    <?php elseif ($leavecredit < 1): ?>
                        <button type="button" onclick="openDialogModal('leaveCreditAlert');" class="btn btn-warning">Submit Request</button>
                        <dialog id="leaveCreditAlert" class="col-lg-4 border-0 rounded shadow p-0">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <div class="align-content-center">
                                            <p class="fw-bold fs-6 m-0">Leave Credit Alert</p>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="closeDialog()">X</button>
                                    </div>
                                </div>
                                <div class="card-body border-start border-5 border-danger">
                                    <div class=" d-flex align-items-start p-4" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill fs-3 text-warning me-3"></i>
                                        <div>
                                            <h5 class="mb-1">Leave Request Not Allowed</h5>
                                            <p class="mb-0">
                                                Your current leave credit is <strong>0</strong>. You are not eligible to apply for leave at this time.
                                                <br>
                                                <strong>If leave is absolutely necessary</strong>, the requested days will be counted as <strong>double</strong>
                                                and the equivalent amount will be deducted from your salary.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="closeDialog()">Cancel</button>
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="closeDialog()">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </dialog>
                        <div class="alert alert-danger d-flex align-items-start p-4 mt-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning me-3"></i>
                            <div>
                                <h5 class="mb-1">Leave Request Not Allowed</h5>
                                <p class="mb-0">
                                    Your current leave credit is <strong>0</strong>. You are not eligible to apply for leave at this time.
                                    <br>
                                    <strong>If leave is absolutely necessary</strong>, the requested days will be counted as <strong>double</strong>
                                    and the equivalent amount will be deducted from your salary.
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="fw-bold m-0"><?= $department_name ?> Department Leaves </h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>From</th>
                            <th>to</th>
                            <th>Days</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($department_leaves as $dept_leave): ?>

                            <tr>
                                <td><?= $dept_leave->NAME ?></td>
                                <td><?= $dept_leave->FROM_DATE ?></td>
                                <td><?= $dept_leave->TO_DATE ?></td>
                                <td><?= $dept_leave->DAYS ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function formatDateToDDMMYYYY(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        const year = date.getFullYear();

        return `${day}-${month}-${year}`;
    }
</script>
<script>
    let department_leave_person_count = <?= $department_leave_person_count ?>;
    let department_leaves_count = <?= $department_leaves_count ?>;

    let departmentLeaves = <?= json_encode($department_leaves) ?>;
    let leaveDatesArr = [];
    departmentLeaves.forEach(leave => {
        leaveDatesArr.push({
            FROM_DATE: formatDateToDDMMYYYY(new Date(leave.FROM_DATE)),
            TO_DATE: formatDateToDDMMYYYY(new Date(leave.TO_DATE))
        });
    });
</script>
<script>
    // Convert to Date objects
    const parsedRanges = leaveDatesArr.map(range => {
        const [fd, fm, fy] = range.FROM_DATE.split('-');
        const [td, tm, ty] = range.TO_DATE.split('-');
        fromDate = new Date(`${fy}-${fm}-${fd}`)
        toDate = new Date(`${ty}-${tm}-${td}`);
        fromDate.setHours(0, 0, 0, 0);
        toDate.setHours(0, 0, 0, 0); // Adjust to include the start date
        return {
            from: fromDate,
            to: toDate
        };
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>
<script>
    const picker = new easepick.create({
        element: "#datepicker",
        css: [
            "/css/easepick.css",
        ],
        format: "DD-MM-YYYY",
        zIndex: 10,
        LockPlugin: {
            minDays: 0,
            inseparable: true,
            filter: (date) => {

                if (department_leave_person_count > department_leaves_count) {

                    return (date.getDay() === 0);
                } else {
                    return parsedRanges.some(range => {

                        return ((date >= range.from) && (date <= range.to)) || (date.getDay() ===
                            0);
                    });
                }

            },
            selectForward: true,
            minDate: new Date()
        },
        RangePlugin: {
            delimiter: "/"
        },
        plugins: [
            "RangePlugin",
            "LockPlugin"
        ],

    })
</script>