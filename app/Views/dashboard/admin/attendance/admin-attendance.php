<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between flex-column flex-md-column flex-lg-row">
                    <h5 class="fw-bold m-0"><?= getMonthName($month) ?> <?= $year ?> Attendance</h5>
                    <?= view("dashboard/components/month-year-selector"); ?>
                </div>
            </div>

            <div class="card-body">
                <ul class="d-none">

                    <?php foreach ($Holidays as $holiday): ?>
                        <data class="holidaydate" data-holidaydate="<?= $holiday->DATE ?>"
                            data-holiday="<?= $holiday->HOLIDAY ?>"><?= $holiday->DATE ?></data>
                    <?php endforeach; ?>

                    <?php foreach ($AttendanceStrength as $entry): ?>
                        <data class="users-on-date" data-date="<?= explode('-', $entry->DATE)[2] ?>"
                            data-totalemp="<?= $entry->TOTAL_USERCOUNT ?>" data-usercount=""><?= $entry->DATE ?></li>
                        <?php endforeach; ?>

                </ul>


                <div>
                    <span class="badge green text-dark p-2">Marked Attendance</span>
                    <span class="badge red text-light p-2">Sundays</span>
                    <span class="badge text-bg-info p-2">Holidays</span>
                    <span class="badge text-bg-light border p-2">No Attendance</span>
                </div>
                <hr>
                <?= view("dashboard/components/calender-attendance-strength"); ?>
            </div>
        </div>


    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight-showPresentEmployees"
    aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Attendance Details of <span id="date"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Name</th>
                    <th>Punch In Time</th>
                    <th>Punch Out Time</th>
                </tr>
            </thead>
            <tbody id="table-body">

            </tbody>
        </table>
    </div>
</div>


<script>
    function fetchAttendanceDate(date) {

        fetch('/attendancebydate?date=' + date)
            .then(response => response.json())
            .then(data => {

                renderList(data, date);
            })
            .catch(error => {
                console.error('Error fetching packages:', error);
            });
    }

    function clearList() {
        const tableBody = document.getElementById('table-body');
        const clickeddate = document.getElementById('date');
        clickeddate.textContent = ""; // Clear existing content
        tableBody.innerHTML = ""; // Clear existing content
    }

    function renderList(data, date) {
        const tableBody = document.getElementById('table-body');
        const clickeddate = document.getElementById('date');
        clickeddate.textContent = ""; // Clear existing content
        tableBody.innerHTML = ""; // Clear existing content
        data.forEach((entry, index) => {
            let month = entry.DATE.split('-')[1];
            let year = entry.DATE.split('-')[0];
            const listItem = `
        <tr>
            <td>${index+1}</td>
            <td><a href="/employee-attendance/${entry.ID}?month=${month}&year=${year}">${entry.NAME}</a></td>
            <td>${(entry.CREATED_ON.split(' ')[1] != null) ? entry.CREATED_ON.split(' ')[1] : 'Not Attended'}</td>
            <td>${(entry.UPDATED_AT != null) ? entry.UPDATED_AT.split(' ')[1] : 'Yet to be Done'}</td>
        </tr>
        `;
            tableBody.insertAdjacentHTML('beforeend', listItem);
        })
        clickeddate.textContent = date;
        const event = new Event('fetchDone');
        window.dispatchEvent(event);
    }

    function showPresentEmployees(date) {
        if (this.event.target.dataset.fetch) {
            fetchAttendanceDate(date);

        } else {
            clearList();
            return 0;
        }

    }
</script>