<?php

$date = new DateTime("$year-$month-01");
$totalDays = (int)$date->format('t');     // Total days in month
$startingDay = (int)$date->format('w');   // 0 (Sun) to 6 (Sat)
$day = 1; // Counter to track day of month

?>

<div class="calendarGrid" id="calendarGrid">
    <div class="day-name">Sun</div>
    <div class="day-name">Mon</div>
    <div class="day-name">Tue</div>
    <div class="day-name">Wed</div>
    <div class="day-name">Thu</div>
    <div class="day-name">Fri</div>
    <div class="day-name">Sat</div>
    <?php for ($i = 0; $i < $startingDay; $i++): ?>
        <div class="empty"></div>
    <?php endfor; ?>

    <?php for ($i = 1; $i <= $totalDays; $i++): ?>
        <div class="day" role='button' data-date='<?= $i ?>'>
            <div class="pe-none">
                <?= $i ?>
                <hr class="mt-0">
                <div class="timing"></div>
            </div>
        </div>
    <?php endfor; ?>

</div>

<script>
    function showAttendanceandLeave() {
        const days = document.querySelectorAll('.day');
        const presentDates = document.querySelectorAll('.present-date');
        const leaveDates = document.querySelectorAll('.leave-date');
        const holidayDates = document.querySelectorAll('.holidaydate');
        const joiningDates = document.querySelectorAll('.joiningDate');

        const urlParams = new URLSearchParams(window.location.search);
        const currentDate = new Date();
        const currentYear = urlParams.get('year') || currentDate.getFullYear();
        const currentMonth = urlParams.get('month') || (currentDate.getMonth() + 1);

        joiningDates.forEach((joining) => {
            const [year, month, day] = joining.dataset.joining.split('-').map(Number);
            if (parseInt(month) == currentMonth && parseInt(year) == currentYear) {
                const index = day - 1;
                const dayElem = days[index];
                dayElem.classList.add('joining');
                dayElem.querySelector('.timing').textContent = `Account Created`;
            }
        });

        holidayDates.forEach((holiday) => {
            const index = parseInt(holiday.dataset.holidaydate.split('-')[2]) - 1;
            const dayElem = days[index];
            dayElem.classList.add('holiday');
            dayElem.querySelector('.timing').textContent = holiday.dataset.holiday;
        });

        leaveDates.forEach((date) => {
            const startIndex = parseInt(date.dataset.leavedate.split('-')[2]) - 1;
            const leaveDays = parseInt(date.dataset.leavedays);
            const leaveReason = date.dataset.leavereason;
            const leaveType = date.dataset.leavetype;
            const leaveTypeLabel = leaveType.replaceAll(" ", "");
            const leaveTypeClass = leaveTypeLabel.includes('|') ? leaveTypeLabel.split('|')[1] : leaveTypeLabel;

            const dayElem = days[startIndex];
            dayElem.dataset.bsToggle = 'tooltip';
            dayElem.dataset.bsPlacement = 'top';
            dayElem.dataset.bsTitle = leaveReason;
            dayElem.querySelector('.timing').innerHTML = leaveType;

            for (let i = 0; i < leaveDays; i++) {
                const currentDayElem = days[startIndex + i];
                currentDayElem.classList.add('leave', leaveTypeClass);
            }
        });

        presentDates.forEach((date) => {
            const index = parseInt(date.dataset.present.split('-')[2]) - 1;
            const punchin = date.dataset.punchin.split(' ')[1] || 'Yet to be Done';
            const punchout = date.dataset.punchout.split(' ')[1] || 'Yet to be Done';
            const dayElem = days[index];

            dayElem.classList.add('present');
            dayElem.querySelector('.timing').innerHTML = `${punchin} / ${punchout}`;
            dayElem.dataset.bsToggle = 'tooltip';
            dayElem.dataset.bsPlacement = 'top';
            dayElem.dataset.bsTitle = getTimeDifference(punchin, punchout);
        });
    }

    showAttendanceandLeave();

    function getTimeDifference(punchin, punchout) {
        const [hours1, minutes1] = punchin.split(':').map(Number);
        const [hours2, minutes2] = punchout.split(':').map(Number);
        const totalMinutes1 = (hours1 * 60) + minutes1;
        const totalMinutes2 = (hours2 * 60) + minutes2;
        let diffMinutes = totalMinutes2 - totalMinutes1;
        if (diffMinutes < 0) {
            diffMinutes += (24 * 60); // Add 24 hours in minutes
        }

        // Convert the difference back to hours and minutes
        const diffHours = (Math.floor(diffMinutes / 60)).toString().padStart(2, '0');
        const remainingMinutes = (diffMinutes % 60).toString().padStart(2, '0');

        if (diffHours == 'NaN' || remainingMinutes == 'NaN') {
            return 'Yet to be Done';
        } else {
            return diffHours + ':' + remainingMinutes + ' hrs';
        }


        /*return {
            hours: diffHours,
            minutes: remainingMinutes
        };*/
    }
</script>