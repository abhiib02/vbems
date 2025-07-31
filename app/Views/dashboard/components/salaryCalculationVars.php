<?php

// All Variables is Coming here from Dashboard Controller Functions and No Data is Directly Coming in Component 
// This Component is taking these Variables from that Dashboard Controller and After Calculating Providing New Variables to use by page or other component 

$date = new DateTime("$year-$month-01");
$totalDays = (int)$date->format('t');     // Total days in month
$totalDays_salaryCalculation = 30;
$attendedDays = count($attendance);
$halfDay = count($half_days);
$fullDay = count($full_days);
$doubleLeaveDays = ($zeroleavecredit->DAYS) * 2;
$totalWorkingDays = (($fullDay + ($halfDay/2)) + ($paid_leaves - $doubleLeaveDays) + ($sundays - ($sundays_in_leaves + $sundayBeforeJoining)));

$coutable_sunday = $sundays - ($sundays_in_leaves + $sundayBeforeJoining);
$HolidayNotOnSunday = $nonSundayHolidays;
$minimumDayAttendance = 15;

$ExactWorkingDays = ($totalDays - ($sundays + $HolidayNotOnSunday));
$attendancePercentage = round(($attendedDays / $ExactWorkingDays) * 100, 2);
$DayPay = round($monthsalary / $totalDays_salaryCalculation, 2);
$calculatedSalary = ($attendedDays >= $minimumDayAttendance) ? round($totalWorkingDays * $DayPay) : 0;

$dataArray = [
    'minimumDayAttendance'=> $minimumDayAttendance,
    'month' => $month,
    'year' => $year,
    'totalDays_salaryCalculation' => $totalDays_salaryCalculation,
    'totalWorkingDays' => $totalWorkingDays,
    'ExactWorkingDays' => $ExactWorkingDays,
    'halfDay' => $halfDay,
    'fullDay' => $fullDay,
    'attendedDays' => $attendedDays,
    'sundays' => $sundays,
    'paid_leaves' => $paid_leaves,
    'zeroCreditLeaveDays' => $zeroleavecredit->DAYS,
    'doubleLeaveDays' => $doubleLeaveDays,
    'coutable_sunday' => $coutable_sunday,
    'attendancePercentage' => $attendancePercentage,
    'DayPay' => $DayPay,
    'calculatedSalary' => $calculatedSalary,
    'joiningDate' => $joiningDate,
    'Holidays' => $Holidays,
    'attendance' => $attendance,
    'approvedLeaves' => $approvedLeaves,
    'employeename' => $employeename,
];
