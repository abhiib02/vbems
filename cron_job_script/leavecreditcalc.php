<?php
// URL to be called
$url = 'https://'.$_SERVER['HTTP_HOST']."/m-lc-calc";
$logfile = __DIR__ . "/cronlog.txt";
$log = date('Y-m-d H:i:s') . " - ";
// Initialize cURL
$ch = curl_init();

// Set options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Prevents direct output
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set timeout

// Execute request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    $log .= "Some Error Occured | ".json_encode($ch);
} else {
    $log .= "Cron Job successful";
    //echo "URL called successfully. Response:\n";
    //echo $response;
}
// Close cURL
curl_close($ch);

file_put_contents($logfile, $log, FILE_APPEND);
