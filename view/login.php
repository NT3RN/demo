<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/login.css">
    <title>Login - SmartCafe</title>
</head>
<body>
    <div id="loginDiv">
        <form method="post" action="../controller/authController.php">
            <div class="logo-container">
                <a href="../index.php">
                    <img src="../assets/logo.png" alt="SmartCafe Logo" class="logo">
                </a>
            </div>
            
            <h1>Login to Smart Cafe</h1>
            
            <div>
                <small class="classDanger" id="loginErr" style="color: red;">
                    <?php
                    if (isset($_GET["loginErr"])) {
                        echo htmlspecialchars($_GET["loginErr"]);
                    }
                    if (isset($_GET["invalidUser"])) {
                        echo htmlspecialchars($_GET["invalidUser"]);
                    }
                    ?>
                </small>
            </div>
            
            <div class="input-group">
                <input type="email" name="email" id="email" placeholder="Enter your email" autocomplete="off">
                <span id="emailErr" style="color: red;">
                    <?php
                    if (isset($_GET["emailErr"])) {
                        echo htmlspecialchars($_GET["emailErr"]);
                    }
                    ?>
                </span>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="off">
                <span id="passErr" style="color: red;">
                    <?php
                    if (isset($_GET["passErr"])) {
                        echo htmlspecialchars($_GET["passErr"]);
                    }
                    ?>
                </span>
            </div>
            
            <input type="submit" name="submit" value="Login">
            
            <div class="forgetpass">
                <p><a href="register.php">Can't access your account?</a></p>
            </div>
        </form>
    </div>
</body>
</html>