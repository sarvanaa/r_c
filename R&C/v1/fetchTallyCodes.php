<?php 

header("Access-Control-Allow-Origin: *"); // Allows all origins
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allowed request methods
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With"); // Corrected header for allowed headers

require_once '../includes/DbOperations.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_ID = $_POST['staff_ID'];

    $db = new DBOperations(); // Create instance of DbOperations
    $TALLY = $db->fetchTallyCodes($staff_ID); // Call function through the instance
    echo json_encode($TALLY);
}
?>

