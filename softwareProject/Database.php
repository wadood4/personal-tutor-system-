<?php
$servername = "localhost"; // or the server address
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$database = "project1_dp"; // your database name

// Create connection
try{
    $conn = mysqli_connect($servername,
        $username,
        $password,
        $database);
}catch (mysqli_sql_exception){
    echo "could not connect <br>";
}
?>