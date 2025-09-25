<?php
    require_once ("userController.php");

    $emailErr ="";
    $passErr ="";
    $hasErr = false;
    if(($_SERVER["REQUEST_METHOD"]=="POST") && (isset($_POST["submit"])))
    {
        
        $email = trim($_POST["email"]);
        $pass = trim($_POST["password"]);

        if(empty($email)){
            $emailErr = "Email can't be empty";
            $hasErr = true;
        }

        if (empty($pass)) {
            $passErr = "Password can't be empty";
            $hasErr = true;
        }

        if($hasErr){
            header("Location: ../view/login.php?emailErr =$emailErr&passErr=$passErr");
            exit();
        }
        else
        {
            $users = searchUser($_POST["email"], $_POST["password"]);
            if(!$users){
                header("Location:../view/login.php?invalidUser = Invalid User");
            }
            else
            {
                session_start();
                $_SESSION["user_id"] = $users['user_id'];
                $_SESSION["email"]=$users["email"];
                $_SESSION["role"]=$users["role"];
                if($users["role"]=="Admin"){
                    header("Location:../view/admin/adminDashboard.php");
                }
                elseif($users["role"]=="Manager"){
                    header("Location: ../view/manager/managerDashboard.php");
                }
                else{
                    header("Location:../view/customer/customerDashboard.php");
                }
            }
        }
    }

?>