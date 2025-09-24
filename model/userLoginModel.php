<?php
    require_once("dbConnect.php");

    function authUser($email, $pass){
        $conn = getConnect();
        
        $sql= "SELECT * FROM users where email='$email' and password ='$pass'";
        
        $result=mysqli_query($conn, $sql);

        return mysqli_fetch_assoc($result);
    }

?>