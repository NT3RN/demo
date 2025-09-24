<?php
    require_once("../model/userLoginModel.php");

    function searchUser($email, $pass){
        return authUser($email, $pass);
    }
?>