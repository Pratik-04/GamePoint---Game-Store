<?php
$conn = new mysqli("localhost","root","","gamepoint");

if($conn->connect_error){
    die("Database Error");
}
?>