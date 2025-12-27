<?php
session_start();
require_once "conn.php";

// force JSON output
header_remove("Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// ----- STATS -----
$stats = [
    "total"          => $conn->query("SELECT COUNT(*) AS t FROM courtship_registrations")->fetch_assoc()['t'],
    "courtship"      => $conn->query("SELECT COUNT(*) AS t FROM courtship_registrations WHERE category='courtship'")->fetch_assoc()['t'],
    "soon_to_wed"    => $conn->query("SELECT COUNT(*) AS t FROM courtship_registrations WHERE category='soon-to-wed'")->fetch_assoc()['t'],
    "newly_married"  => $conn->query("SELECT COUNT(*) AS t FROM courtship_registrations WHERE category='newly-married'")->fetch_assoc()['t'],
    "mature_single"  => $conn->query("SELECT COUNT(*) AS t FROM courtship_registrations WHERE category='mature-single'")->fetch_assoc()['t'],
    "physical"       => $conn->query("SELECT COUNT(*) AS t FROM courtship_registrations WHERE attendance_mode='physical'")->fetch_assoc()['t'],
    "virtual"        => $conn->query("SELECT COUNT(*) AS t FROM courtship_registrations WHERE attendance_mode='virtual'")->fetch_assoc()['t'],
];

// ----- DATA -----
$records = [];
$q = $conn->query("SELECT * FROM courtship_registrations ORDER BY id DESC");
while($row = $q->fetch_assoc()){
    $records[] = $row;
}

echo json_encode([
    "status" => "success",
    "stats" => $stats,
    "records" => $records
]);