<?php 
function getMonthName($monthNumber): ?string {
    if (!is_numeric($monthNumber) || $monthNumber < 1 || $monthNumber > 12) {
        return null;
    }
    return DateTime::createFromFormat('!m', $monthNumber)->format('F');
}
