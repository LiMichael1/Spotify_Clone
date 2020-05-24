<?php
    class Account {
        private $con;
        private $errorArray;

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = array();
        }

        //login
        public function login($un, $pw) {
            $pw = hash('sha256', $pw);
            $query = "SELECT COUNT(1) FROM users WHERE username = ? AND password = ?";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param('ss', $un, $pw);
            $stmt->execute();
            $stmt->bind_result($found);
            $stmt->fetch();
            if($found) {
                return true;
            }
            else {
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            }

        }

        //validate registeration form input then insert into database
        public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
            $this->validateUsername($un);
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateEmails($em, $em2);
            $this->validatePasswords($pw, $pw2);

            // no errors
            if(empty($this->errorArray)) {
                //insert into the database
                return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
            }   
            else {
                return false;
            }
        }
        //Check if error exists
        public function getError($error) {
            if(!in_array($error, $this->errorArray)) {
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        }

        private function insertUserDetails($un, $fn, $ln, $em, $pw) {
            // encrypt the password with md5 method
            $encryptedPw = hash('sha256', $pw);
            $profilePic = 'assets/images/profile-pics/hair.jpg';
            $date = date('Y-m-d');
            //prepare the statement to prevent SQL injections
            $query = "INSERT INTO users (username, firstName, lastName, email, password, signUpDate, profilePic) VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param('sssssss', $un, $fn, $ln, $em, $encryptedPw, $date, $profilePic);
            
            //returns if prepared Statement was successful
            return $stmt->execute();
        }

        private function validateUsername($un) {
            if(strlen($un) > 25 || strlen($un) <5) {
                array_push($this->errorArray, Constants::$usernameCharacters);
                return;
            }
            //CHECK IF USERNAME EXISTS
            $query = "SELECT COUNT(1) FROM users WHERE username = ?";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param('s', $un);
            $stmt->execute();
            // check if result was found
            $stmt->bind_result($found);
            $stmt->fetch();
            if($found) {
                array_push($this->errorArray, Constants::$usernameAlreadyExists);
                return;
            }
        }

        private function validateFirstName($fn) {
            if(strlen($fn) > 25 || strlen($fn) <2) {
                array_push($this->errorArray, Constants::$firstNameCharacters);
                return;
            }
        }
    
        private function validateLastName($ln) {
            if(strlen($ln) > 25 || strlen($ln) <2) {
                array_push($this->errorArray, Constants::$lastNameCharacters);
                return;
            }
        }
    
        private function validateEmails($em, $em2) {
            //check if emails equal
            if($em != $em2) {
                array_push($this->errorArray, Constants::$emailsDoNotMatch);
                return;
            }
            //uses regular expression to check email format 
            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }
            //CHECK IF EMAIL ALREADY EXISTS
            $query = "SELECT COUNT(1) FROM users WHERE email = ?";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param('s', $em);
            $stmt->execute();
            // check if result was found
            $stmt->bind_result($found);
            $stmt->fetch();
            if($found) {
                array_push($this->errorArray, Constants::$emailAlreadyExists);
                return;
            }
        }
    
        private function validatePasswords($pw, $pw2) {
            if($pw != $pw2) {
                array_push($this->errorArray, Constants::$passwordsDoNotMatch);
                return;
            }
            //check for unwanted characters - if not all letters or numbers then it's wrong
            if(preg_match('/[^A-Za-z0-9]/', $pw)) {
                array_push($this->errorArray, Constants::$passwordNotAlphaNumeric);
                return;
            }

            if(strlen($pw) > 25 || strlen($pw) <5) {
                array_push($this->errorArray, Constants::$passwordCharacters);
                return;
            }

        }

    }


?>