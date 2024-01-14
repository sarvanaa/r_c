<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

require_once '../includes/DbOperations.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['TALLY'])) {
        // Create an instance of DbOperations
        $db = new DbOperations();

        // Fetch data based on the TALLY code
        $TALLYCode = $_POST['TALLY'];
        $data = $db->getDataByTally($TALLYCode);

        if ($data) {
            $response['error'] = false;
            $response['message'] = 'Data fetched successfully';
            $response['data'] = $data;
        } else {
            $response['error'] = true;
            $response['message'] = 'No data found for the provided TALLY code';
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Missing TALLY code in the request';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid Request Method';
}

header('Content-Type: application/json');
echo json_encode($response);

?>
