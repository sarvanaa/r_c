<?php

header("Access-Control-Allow-Origin: *"); // Allows all origins
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allowed request methods
header("Access-Control-Allow-Headers: Content-Type:application/json");



class DBConnect{
    private $con;
    function __construct(){

    }

    function connect(){
        include_once dirname(__FILE__).'/constants.php';
        $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


        if(mysqli_connect_errno()){
                echo "Failed to connect with database".mysqli_connect_errno();
        }
        return $this->con;

    }
}