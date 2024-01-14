<?php

header("Access-Control-Allow-Origin: *"); // Allows all origins
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allowed request methods
header("Access-Control-Allow-Headers: Content-Type:application/json");



class DBOperations{

    private $con;
    function __construct(){

        require_once dirname(__FILE__).'/DBConnect.php';

        $db = new DBConnect();

        $this->con = $db->connect();

    }


    public function isValidUser($webmail_ID, $username) {
        $stmt = $this->con->prepare("SELECT * FROM user WHERE webmail_ID = ? AND username = ?");
        $stmt->bind_param("ss", $webmail_ID, $username);
        $stmt->execute();
        $stmt->store_result();
    
        if($stmt->num_rows>0){
            return 1;
        }else{
            return 0;
        }
    }
    
    
    public function validUserCredentials($webmail_ID, $username) {
        $stmt = $this->con->prepare("SELECT * FROM user WHERE webmail_ID = ? OR username = ?");
        $stmt->bind_param("ss", $webmail_ID, $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    

    public function createPassword($id , $password){

        $stmt = $this->con->prepare("UPDATE `user` SET `password` = ? WHERE `user`.`id` = ?;");
        $stmt->bind_param("ss" , $password , $id);
        if( $stmt->execute()){
            return true;
        }else{
            return false;
        }
         
    }

    public function isPassAlreadySet($id){

        $stmt = $this->con->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("s" , $id);
        $stmt->execute();
        $stmt->store_result();
        // $result = $stmt->get_result()->fetch_assoc();
        if($stmt['password']){
            return true;
        }else{
            return false;
        }
        
    }


    public function resetPassword($webmail_ID, $password) {
        // First, check if the webmail_ID exists in the database
        $checkStmt = $this->con->prepare("SELECT COUNT(*) FROM `user` WHERE `webmail_ID` = ?;");
        $checkStmt->bind_param("s", $webmail_ID);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();
    
        // If the count is 0, then the webmail_ID does not exist
        if ($count == 0) {
            return ['success' => false, 'message' => 'Invalid email ID'];
        }
    
        // If the webmail_ID exists, proceed with password update
        $stmt = $this->con->prepare("UPDATE `user` SET `password` = ? WHERE `webmail_ID` = ?;");
        $stmt->bind_param("ss", $password, $webmail_ID);
    
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Password reset successfully'];
        } else {
            return ['success' => false, 'message' => 'Password reset failed'];
        }
    }
    

    
    

    public function isLoginCredentialCorrect($username , $password){
        $stmt = $this->con->prepare("SELECT * FROM user WHERE password = ? AND username = ?");
        $stmt->bind_param("ss" , $password , $username);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
            return 1;
        }else{
            return 0;
        }
    }

    

    public function getUserByStaffId($username){

        $stmt = $this->con->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s"  , $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
         
    }

   
  

    public function projectDetailsByStaffId($staff_ID) {
        // Prepare the statement with a placeholder for the staff_ID
        $stmt = $this->con->prepare("SELECT content FROM horizontal WHERE JSON_EXTRACT(content, '$.staff_ID') = ?");
    
        if ($stmt === false) {
            // Handle error, e.g., log it, return or throw an exception
            trigger_error('Statement prepare failed: ' . $this->con->error, E_USER_ERROR);
            return null;
        }
    
        // Bind the staff_ID parameter to the prepared statement
        $stmt->bind_param('s', $staff_ID); // 's' denotes the type 'string'
    
        // Execute the statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
    
        
            return $result->fetch_assoc();
        } else {
            // Handle execution error
            trigger_error('Statement execute failed: ' . $stmt->error, E_USER_ERROR);
            return null;
        }
    }
    


    function fetchTallyCodes($staff_ID) {
        $sql = "SELECT DISTINCT JSON_EXTRACT(content, '$.TALLY') AS TallyCode FROM horizontal WHERE JSON_EXTRACT(content, '$.staff_ID') = ?";
        $stmt = $this->con->prepare($sql);
    
        if (!$stmt) {
            die("Error in prepare statement: " . $this->con->error);
        }
    
        $stmt->bind_param("s", $staff_ID);
        $stmt->execute();
    
        if ($stmt->error) {
            die("Error in execute statement: " . $stmt->error);
        }
    
        $result = $stmt->get_result();
    
        if (!$result) {
            die("Error in get_result: " . $this->con->error);
        }
    
        $TALLY = array();
        while ($row = $result->fetch_assoc()) {
            // Use the alias TallyCode here
            $TALLY[] = $row['TallyCode'];
        }
    
        $stmt->close();
    
        return $TALLY;
    }
    
    
    public function getDataByTally($tally) {
       
        $sql = "SELECT content FROM horizontal WHERE JSON_EXTRACT(content, '$.TALLY') = ?";
        
        // Prepare the SQL statement
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
           
            error_log("Statement preparation failed: " . $this->con->error);
            return null;
        }
        // Bind the $tally parameter to the SQL statement
        $stmt->bind_param("s", $tally);
        if (!$stmt->execute()) {
           
            error_log("Execute failed: " . $stmt->error);
            return null;
        }

     
        $result = $stmt->get_result();
        if (!$result) {
            error_log("Getting result failed: " . $this->con->error);
            return null;
        }

    
        $data = $result->fetch_all(MYSQLI_ASSOC);

      
        $stmt->close();

        return $data;
    }
    
    }
    
    


