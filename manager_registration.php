<?php
    include_once("settings.php");
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset"]))
    {
        session_unset();
        session_destroy();
        header("Location: manager_registration.php");
    }
    function clean_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $_SESSION["first_name"] = clean_input($_POST["first_name"]);
        $_SESSION["last_name"] = clean_input($_POST["last_name"]);
        $_SESSION["username"] = clean_input($_POST["username"]);
        $_SESSION["password"] = password_hash(clean_input($_POST["password"]), PASSWORD_DEFAULT);
        
        //https://www.phpliveregex.com/ was accessed on the 20th of May 2025, to understand how php regex patterns work
        $pattern = "/(?=(.*[a-zA-Z]))(?=(.*\d))(?=(.*[\W_]))^[a-zA-Z\d\W_]+$/";

        $stmt = $conn -> prepare("SELECT * FROM managers WHERE username = ?;");
        $stmt -> bind_param("s", $_SESSION["username"]);
        $stmt -> execute();
        $result = $stmt -> get_result();
        if (!(mysqli_num_rows($result) > 0))
        {
            if ($_POST["password"] == $_POST["confirm_password"])
            {
                if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["username"]) && !empty($_POST["password"]))
                {
                    if (preg_match($pattern, $_POST["password"]))
                    {
                        $stmt = $conn -> prepare("INSERT INTO managers (username, password, first_name, last_name)
                        VALUES (?, ?, ?, ?);");
                        $stmt -> bind_param("ssss", $_SESSION["username"], $_SESSION["password"], $_SESSION["first_name"], $_SESSION["last_name"]);
                        $stmt -> execute();

                       header("Location: manage_login.php");
                    }
                }
            }
        }
    }
    else
    {
        $_SESSION["first_name"] = "";
        $_SESSION["last_name"] = "";
        $_SESSION["username"] = "";
        $_SESSION["password"] = "";
        $_SESSION["confirm_password"] = "";
        $_SESSION["account_ready"] = false;
    }
    $page = "manager registration";
    include_once("header.php");
?>
<main class="thin-main">
    <h1>Manager Registration</h1>
    <p></p>
    <h3>Register to become a website manager today!</h3>
    <?php
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["username"]) && !empty($_POST["password"]))
    {
        if ($_POST["password"] == $_POST["confirm_password"])
        {
            echo("<p>data is ready to be sent!!!</p>");
        }
    }
    else if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["reset"]))
    {
        echo("Do not leave any fields blank!!!");
    }
    ?>
    <form action="manager_registration.php" method="post">
        <fieldset>
            <legend>Details:</legend>
            <label for="first_name" class="required">First name</label>
            <input type="text" id="first_name" name="first_name" value=<?php echo($_SESSION["first_name"]);?>>
            <label for="last_name" class="required">Last name</label>
            <input type="text" id="last_name" name="last_name" value=<?php echo($_SESSION["last_name"]);?>>
        </fieldset>
        <br>
        <fieldset>
            <legend>Login credentials:</legend>
            <?php
                $stmt = $conn -> prepare("SELECT * FROM managers WHERE username = ?;");
                $stmt -> bind_param("s", $_SESSION["username"]);
                $stmt -> execute();
                $result = $stmt -> get_result();
                if (mysqli_num_rows($result) > 0)
                {
                    echo("<p>Username is taken!!!</p>");
                }
            ?>
            <label for="username" class="required">Username</label>
            <input type="text" id="username" name="username" value=<?php echo($_SESSION["username"]);?>>
            <?php
                if(isset($_POST["password"]) && $_POST["password"] != $_POST["confirm_password"])
                {
                    echo("<p>Passwords do not match!!!</p>");
                }
                if(isset($_POST["password"]) && !preg_match($pattern, $_POST["password"]))
                {
                    echo("<p>Password needs at least 1 lower-case character, uper-case character, and symbol!!!</p>");
                }
            ?>
            <label for="password" class="required">Password</label>
            <input type="password" id="password" name="password" value=<?php if ($_SERVER["REQUEST_METHOD"] == "POST"){echo($_POST["password"]);}?>>
            <label for="confirm_password" class="required">Confirm password</label>
            <input type="password" id="confirm_password" name="confirm_password" value=<?php if ($_SERVER["REQUEST_METHOD"] == "POST"){echo($_POST["confirm_password"]);}?>>
        </fieldset>
        <br>
        <input type="submit" class="form-buttons">
        <input type="submit" name="reset" value="Reset" class="form-buttons">
    </form>
</main>
<?php
    include_once("footer.php");
?>