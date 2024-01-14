<?php 

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type:application/json");

require_once '../includes/DBOperations.php';

$response = array(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $webmail_ID = isset($_POST['webmail_ID']) ? $_POST['webmail_ID'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Check if inputs are not empty
    if (empty($webmail_ID) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Webmail ID or Password is missing']);
        exit();
    }

    $db = new DbOperations(); 

    // Attempt to reset the password
    $result = $db->resetPassword($webmail_ID, $password);

    // Directly output the result as JSON
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

