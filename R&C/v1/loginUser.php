<?php 


header("Access-Control-Allow-Origin: *"); // Allows all origins
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allowed request methods
header("Access-Control-Allow-Headers: Content-Type:application/json");


require_once '../includes/DBOperations.php';

$response = array(); 


if($_SERVER['REQUEST_METHOD']=='POST'){
	// echo"5";
	if(isset($_POST['username']) and isset($_POST['password'])){
		$db = new DBOperations(); 

        $resust = $db->isLoginCredentialCorrect($_POST['username'], $_POST['password']);
		if($resust == 1){
			// echo "1";
			$user = $db->getUserByStaffId($_POST['username']);
			$response['error'] = false; 
			$response['id'] = $user['id'];
			$response['username'] = $user['username']; 
			// $response['Username'] = $user['username'];
		}else{
			// echo "2";
			$response['error'] = true; 
			$response['message'] = "Invalid username or password";			
		}

	}else{
		//   echo"3";
		$response['error'] = true; 
		$response['message'] = "Required fields are missing";
	}
}
echo json_encode($response);