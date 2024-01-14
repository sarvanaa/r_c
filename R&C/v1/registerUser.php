<?php

header("Access-Control-Allow-Origin: *"); // Allows all origins
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allowed request methods
header("Access-Control-Allow-Headers: Content-Type:application/json");


require_once '../includes/DBOperations.php';


$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){

    if(
        isset($_POST['webmail_ID'] , $_POST['username'], $_POST['password'])
    ){
        //operations
            $db = new DBOperations();

            $result = $db->isValidUser($_POST['webmail_ID'] , $_POST['username'] );
            
            if($result == 1){
                $userResult = $db->validUserCredentials($_POST['webmail_ID'] , $_POST['username']);
                // $isPassResult = $db->isPassAlreadySet($userResult['Id']);
                if(!empty($userResult['password']) ){
                    $response['error'] = true;
                    $response['message'] = "User Already registered";
                }else{
                    if($userResult['id']){
                        $createPassResult = $db->createPassword($userResult['id'],$_POST['password'] );
                        if($createPassResult){
                            $response['error'] = false;
                            $response['message'] = "Registered Successfully";
                        }else{
                            $response['error'] = true;
                            $response['message'] = "Error occured in storing password";
                        }
                    }
                }

                
                   
                
            }
            if($result == 0 ){
                $response['error'] = true;
                $response['message'] = "Invalid Username or staff Id";
            }



    }else{
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
    }

}else{
    $response['error'] = true;
    $response['message'] = "Invalid Request";
}

echo json_encode($response);