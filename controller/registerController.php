<?php
require_once("../model/userRegisterModel.php");

$nameErr = $emailErr = $passErr = $cpassErr = $sqErr = $saErr = "";
$hasErr = false;

if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["submit"])) {

    $name  = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $pass  = trim($_POST["password"] ?? "");
    $cpass = trim($_POST["confirm_password"] ?? "");
    $role  = "Customer"; 
    $sq    = trim($_POST["security_question"] ?? "");
    $sa    = trim($_POST["security_answer"] ?? "");

    
    if ($name === "") { $nameErr = "Username can't  be empty"; $hasErr = true; }
    if ($email === "") { $emailErr = "Email can't  be empty"; $hasErr = true; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $emailErr = "Invalid email  format"; $hasErr = true; }

    if ($pass === "") { $passErr = "Password can't be empty"; $hasErr = true; }
    elseif (strlen($pass) < 4) { $passErr = "Password must be  at least 4 characters"; $hasErr = true; }

    if ($cpass === "") { $cpassErr = "Confirm password required"; $hasErr = true; }
    elseif ($pass !== $cpass) { $cpassErr = "Passwords do not match"; $hasErr = true; }

    $allowedSq = [
        "What is your mother’s maiden name?",
        "What was the name of your first pet?",
        "What city were you born in?"
    ];
    if ($sq === "") {
        $sqErr = "Security question required";
        $hasErr = true;
    } elseif (!in_array($sq, $allowedSq, true)) {
        $sqErr = "Invalid security question selected";
        $hasErr = true;
    }

    if ($sa === "") { $saErr = "Security answer required"; $hasErr = true; }

    
    if (!$hasErr && doseEmailExists($email)) {
        $emailErr = "Email already registered";
        $hasErr = true;
    }

    if ($hasErr) {
        header("Location: ../view/register.php?"
            . "nameErr="  . urlencode($nameErr)
            . "&emailErr=" . urlencode($emailErr)
            . "&passErr="  . urlencode($passErr)
            . "&cpassErr=" . urlencode($cpassErr)
            . "&sqErr="    . urlencode($sqErr)
            . "&saErr="    . urlencode($saErr)
            . "&username=" . urlencode($name)
            . "&email="    . urlencode($email)
            . "&sq="       . urlencode($sq)
            . "&sa="       . urlencode($sa)
        );
        exit();
    }

    $ok = insertUser($name, $email, $pass, $role, $sq, $sa);
    if ($ok) {
       
        header("Location: ../view/login.php?loginErr=" . urlencode("Registration successful. Please login."));
        exit();
    } else {
        header("Location: ../view/register.php?formErr=" . urlencode("Failed to register. Try again."));
        exit();
    }
} else {
    header("Location: ../view/register.php");
    exit();
}
