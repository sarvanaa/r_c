<?php

header("Access-Control-Allow-Origin: *"); // Allows all origins
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allowed request methods
header("Access-Control-Allow-Headers: Content-Type:application/json");

require_once '../includes/DbOperations.php';

$response = array(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['staff_ID'])) {
        $db = new DBOperations();
        $result = $db->projectDetailsByStaffId($_POST['staff_ID']);

        if ($result) {
            // Assuming $result is an associative array
            foreach ($result as $key => $value) {
                $response[$key] = $value;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'No Data Found.';
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Required field missing';
    }
}
    echo json_encode($response);

    