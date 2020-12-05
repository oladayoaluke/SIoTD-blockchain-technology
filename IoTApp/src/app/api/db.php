<?php
 
// get the HTTP method, path and body of the request

// connect to the mysql database
 header("Access-Control-Allow-Origin: *");
$conn = mysqli_connect('localhost', 'root', '', 'nsu_iot_app');
mysqli_set_charset($conn,'utf8');

// close mysql connection
