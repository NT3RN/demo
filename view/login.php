<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <?php

    ?>
    <div id="loginDiv">
        <form method="post" action="../controller/authController.php">
            <h1>Login to Smart Cafe</h1>
            <div>
                <small class="classDanger" id="loginErr">
                    
                </small>
                    <?php
                    if (isset($_GET["loginErr"])) {
                        echo $_GET["loginErr"];
                    }
                    ?>
                </small>
            </div>
            <div class="input-group">
                <input type="email" name="email" id="email" placeholder="Enter your email" autocomplete="off">
                <span id="emailErr" style="color: red;">
                    <?php
                    if (isset($_GET["emailErr"])) {
                        echo $_GET["emailErr"];
                    }
                    ?>
                </span><br>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="off">
                <span id="passErr" style="color: red;">
                    <?php
                    if (isset($_GET["passErr"])) {
                        echo $_GET["passErr"];
                    }
                    ?>
                </span><br>
            </div>
            <input type="submit" name="submit" value="Login">
            <div class="forgetpass">
                <p><a href="register.php">Can't access your account? </a></p>
            </div>
        </form>
        <script src="js/login.js"></script>
</body>

</html>