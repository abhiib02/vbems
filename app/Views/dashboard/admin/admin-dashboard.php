<div class="p-1 mb-4 maingradient rounded-3">
    <div class="container-fluid py-1">
        <h1 class="display-6 fw-bold text-light">Welcome, <?= $name ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-3 mb-4 hide-on-mobile">
        <div class="card">
            <div class="card-header">
                <b>Total Departments</b>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $TotalDepartments ?></h5>
                <p class="card-text">Departments</p>
                <a href="/departments-list" class="btn btn-sm btn-primary">Department List <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-4 hide-on-mobile">
        <div class="card">
            <div class="card-header">
                <b>Total Employees</b>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $TotalEmployees ?></h5>
                <p class="card-text">Employees</p>
                <a href="/employee-list" class="btn btn-sm btn-primary">Employees List <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-4 hide-on-mobile">
        <div class="card">
            <div class="card-header">
                <b>Pending Leave Requests</b>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $leaveRequestsCount ?></h5>
                <p class="card-text">Requests</p>
                <a href="/leaveRequests" class="btn btn-sm btn-primary">Leave Requests <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
            <div class="card-header">
                <b>Next Holiday</b>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= ($nextHoliday->HOLIDAY) ?? 'No Holiday is Feeded' ?></h5>
                <p class="card-text"><?= ($nextHoliday->DATE) ?? '&nbsp;' ?></p>
                <a href="/holidays-list" class="btn btn-sm btn-primary">Holiday List <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-5 col-lg-5 ">
        <div class="card">
            <div class="card-header">
                <b>Today's Attendance</b>
            </div>
            <div class="card-body">
                <ol class="list-group list-group-numbered h-auto" style="max-height: 370px;overflow: auto;">
                    <?php foreach ($todayattendance as $entry): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start ">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold"><?= $entry->USER_ID ?> | <?= $entry->NAME ?></div>
                                <i class="ri-login-circle-line"></i> <?= $entry->CREATED_ON ?>
                                <?= ($entry->PUNCH_OUT) ? ' | <i class="ri-logout-circle-r-line"></i> ' . $entry->UPDATED_AT : ''; ?>
                            </div>
                            <?php if($entry->PUNCH_OUT):?>
                            <span class="fs-3">✅</span>
                            <?php else:?>
                            <span class="fs-3">✔</span>
                            <?php endif;?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-lg-7">
        <div class="card hide-on-mobile">
            <div class="card-header">
                <b>Attendance Strength of <?= date('Y') ?></b>
            </div>
            <div class="card-body">
                <canvas id="annual-attendance"></canvas>
            </div>
        </div>
    </div>


</div>

<script>
    function getWorkingDays(year, month) {
        // month is 1-based for input, but 0-based in JS Date
        const totalDays = new Date(year, month, 0).getDate(); // last day of month
        let sundays = 0;

        for (let day = 1; day <= totalDays; day++) {
            const date = new Date(year, month - 1, day); // JS months = 0-11
            if (date.getDay() === 0) { // Sunday = 0
                sundays++;
            }
        }

        return totalDays;
    }
</script>
<script>
    let month_data = [];
    let HolidaysArr = <?= $HolidaysArr ?>;

    const currentYear = new Date().getFullYear();
    const daysInMonth = (year, month) => new Date(year, month, 0).getDate();

    const DATA_MONTHS = <?= $yearlyAttendance ?>;
    DATA_MONTHS.forEach((month, index) => {
        month_data[month.MONTH - 1] = month.SUM_OF_USERCOUNT / (getWorkingDays(currentYear, month.MONTH) -
            HolidaysArr[index]);
    })

    var ctx = document.getElementById("annual-attendance").getContext("2d");
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ],
            datasets: [{
                label: 'Person Attended Each Working Day',
                data: month_data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    myChart.update();
</script>