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
         <div class="day" role='button' data-bs-toggle='offcanvas' data-bs-target='#offcanvasRight-showPresentEmployees' onclick='showPresentEmployees(`<?= $year ?>-<?= $month ?>-<?= $i ?>`);' data-date='<?= $i ?>'>
             <div class="pe-none">
                 <?= $i ?>
                 <hr class="mt-0">
                 <div class="daydetail"></div>
                 <div class="strength"></div>
             </div>
         </div>
     <?php endfor; ?>

 </div>


 <script>
     function showAttendanceStrength() {
         const days = document.querySelectorAll('.day');
         const UsersCounts = document.querySelectorAll('.users-on-date');
         const holidayDates = document.querySelectorAll('.holidaydate');
         const AllEmployeeCount = <?= $all_employees_count ?>;

         const searchParams = new URLSearchParams(window.location.search);
         const selectedMonth = parseInt(searchParams.get('month'));
         const selectedYear = parseInt(searchParams.get('year'));

         const today = new Date();
         const todayDay = today.getDate();
         const currentMonth = today.getMonth() + 1;
         const currentYear = today.getFullYear();

         const isTodayView =
             (!selectedMonth && !selectedYear) ||
             (selectedMonth === currentMonth && selectedYear === currentYear);

         if (isTodayView) {
             const todayElem = days[todayDay - 1];
             if (todayElem) todayElem.classList.add('fw-bold');
         }

         holidayDates.forEach((holiday) => {
             const index = parseInt(holiday.dataset.holidaydate.split('-')[2]) - 1;
             const dayElem = days[index];
             dayElem.classList.add('holiday');
             dayElem.querySelector('.daydetail').textContent = ' | ' + holiday.dataset.holiday;
         });

         UsersCounts.forEach((entry) => {
             const index = parseInt(entry.dataset.date) - 1;
             const totalEmp = parseInt(entry.dataset.totalemp);
             const strength = ((totalEmp / AllEmployeeCount) * 100).toFixed(2);

             const dayElem = days[index];
             dayElem.style.setProperty('--strength', '100%');
             dayElem.style.setProperty('--degree', '90deg');
             dayElem.querySelector('.strength').textContent = `${totalEmp} Employees`;
             dayElem.dataset.fetch = '1';
         });
     }
     showAttendanceStrength();
 </script>