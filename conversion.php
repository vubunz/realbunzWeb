<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'dateToTimestamp') {
        $inputDate = $_POST['date'];
        $timestamp = strtotime($inputDate . ' 00:00:00');
        echo "<p>Timestamp: $timestamp</p>";
    } elseif ($action === 'timesoff') {
        $inputTimestamp = $_POST['timestampToDate'];
        $formattedDate = date('Y-m-d', $inputTimestamp);
        echo "<p>Date: $formattedDate</p>";
    }
}
?>
