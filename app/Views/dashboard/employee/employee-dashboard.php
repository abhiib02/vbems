<?php include __DIR__ . '../../components/salaryCalculationVars.php' ?>
<?php $Latestleave = $leaves[0] ?? ''; ?>
<div class="p-1 mb-4 maingradient rounded-3">
    <div class="container-fluid py-1">
        <h1 class="display-6 fw-bold text-light">Welcome, <?= $name ?></h1>
    </div>
</div>
<h3></h3>
<hr>
<h1 class="text-center"> </h1>
<div class="row">

    <div class="col-md-6 col-lg-5">
        <?= view("dashboard/components/calculated-salary", $dataArray); ?>

        <?= view("dashboard/components/accumulated-leavecredit"); ?>

        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="align-content-center">
                        <span class="fw-bold">Last Leave Request</span><br>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if ($Latestleave): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Days</th>
                                <th>Request Applied On </th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tr>
                            <td><?= $Latestleave->FROM_DATE ?></td>
                            <td><?= $Latestleave->TO_DATE ?></td>
                            <td><?= $Latestleave->DAYS ?></td>
                            <td><?= $Latestleave->CREATED_ON ?></td>
                            <td class="<?= $Latestleave->STATUS ?>"><?= $Latestleave->STATUS ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <div class="fs-2"><b>No Leave Request</b></div>

                <?php endif; ?>
            </div>
        </div>

    </div>
    <div class="col-md-12 col-lg-7">
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
    let month_data = [];
    //let HolidaysArr = ;

    const currentYear = new Date().getFullYear();
    const daysInMonth = (year, month) => new Date(year, month, 0).getDate();

    const DATA_MONTHS = <?= $employeeYearlyAttendance ?>;
    DATA_MONTHS.forEach((month, index) => {
        month_data[month.MONTH - 1] = month.ATTENDED_DAYS;
    })

    var ctx = document.getElementById("annual-attendance").getContext("2d");
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ],
            datasets: [{
                label: 'Attended Days',
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