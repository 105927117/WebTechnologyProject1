<?php
    include_once("settings.php");
    session_start();
    //this clears the session data if the user hits the reset button
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset"]))
    {
        session_unset();
        session_destroy();
        header("Location: manager_registration.php");
    }

    //the following function removes white space at the start and end of strings, removes the backslashes used as escape characters, and converts special characters into html format
    function clean_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //the following code executes if the user submitted the form
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
            //the following code only runs if both passwords match
            if ($_POST["password"] == $_POST["confirm_password"])
            {
                //the following code only runs if no feilds are empty
                if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["username"]) && !empty($_POST["password"]))
                {
                    //the following code only runs if the password is strong enough
                    if (preg_match($pattern, $_POST["password"]))
                    {
                        //prepare the sql statement without attatching values to the username parameter
                        $stmt = $conn -> prepare("INSERT INTO managers (username, password, first_name, last_name)
                        VALUES (?, ?, ?, ?);");
                        //bind the session variables to correct parameters
                        $stmt -> bind_param("ssss", $_SESSION["username"], $_SESSION["password"], $_SESSION["first_name"], $_SESSION["last_name"]);
                        //run the query
                        $stmt -> execute();


                        session_unset();
                        header("Location: manager_login.php");
                    }
                }
            }
        }
    }
    else
    {
        //if the user has not submitted the form yet, fill the session variables with blank strings, so that the code does not have issues later
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
    //the following code warns the user if any feilds are left empty
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["username"]) && !empty($_POST["password"]))
    {
        //the user does not actually see this code execute, it has been left here for testing purposes
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
    <!--the following form asks for a username, first name, last name, and two matching passwords-->
    <form action="manager_registration.php" method="post">
        <fieldset>
            <legend>Details:</legend>
            <!--the php code is here to make sure that the details that the user just submitted stay on the page after a failed attempt-->
            <label for="first_name" class="required">First name</label>
            <input type="text" id="first_name" name="first_name" value=<?php echo($_SESSION["first_name"]);?>>
            <label for="last_name" class="required">Last name</label>
            <input type="text" id="last_name" name="last_name" value=<?php echo($_SESSION["last_name"]);?>>
        </fieldset>
        <br>
        <fieldset>
            <legend>Login credentials:</legend>
            <?php
                //prepare the sql statement without attatching values to the username parameter
                $stmt = $conn -> prepare("SELECT * FROM managers WHERE username = ?;");
                //bind the session variables to correct parameters
                $stmt -> bind_param("s", $_SESSION["username"]);
                //run the query
                $stmt -> execute();
                //save the query result
                $result = $stmt -> get_result();

                //check if the username is taken, if so, warn the user
                if (mysqli_num_rows($result) > 0)
                {
                    echo("<p>Username is taken!!!</p>");
                }
            ?>
            <label for="username" class="required">Username</label>
            <input type="text" id="username" name="username" value=<?php echo($_SESSION["username"]);?>>
            <?php
                //check if the passwords match, if not, warn the user
                if(isset($_POST["password"]) && $_POST["password"] != $_POST["confirm_password"])
                {
                    echo("<p>Passwords do not match!!!</p>");
                }
                //check the password strength and warn the user if it is poor
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