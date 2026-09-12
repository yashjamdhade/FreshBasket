<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "sql202.infinityfree.com";
$user = "if0_42885839";
$password = "m6TOcQIiEZ0dT";
$database = "if0_42885839_freshbasket";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    error_log("FreshBasket database connection failed: " . mysqli_connect_error());
    http_response_code(500);
    exit("The application is temporarily unavailable. Please try again later.");
}

mysqli_set_charset($conn, "utf8mb4");
?>
