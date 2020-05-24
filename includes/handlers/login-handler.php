<?php

    if(isset($_POST['loginButton'])) {
        //Login Button was pressed
        $username = $_POST['loginUsername'];
        $password = $_POST['loginPassword'];

        $result = $account->login($username, $password);

        
        if($result) {
            //successful login
            //set Session variables to stay logged in 
            $_SESSION['userLoggedIn'] = true;
            $_SESSION['name'] = $username;

            header("Location: index.php");
        }
    }


?>