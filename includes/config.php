<?php
    //Connects to Database file

    //wait til you have all the data to send to the database server
    ob_start(); 
    //enable sessions in php
    session_start();

    $timezone = date_default_timezone_set("America/Los_Angeles");

    $conn = mysqli_connect('localhost', 'root', '', 'spotify');

    if(mysqli_connect_errno()) {
        echo "Failed to connect " . mysqli_connect_errno();
    }
?>