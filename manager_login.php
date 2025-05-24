<?php
session_start();
include_once("settings.php");
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!isset($_SESSION["attempts"]))
{
    $_SESSION["attempts"] = 4;
}
if (!isset($_SESSION["delay_start"]))
{
    $_SESSION["delay_start"] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if ($_SESSION["delay_start"] + 30 < time())
    {
    $_SESSION["username"] = clean_input($_POST["username"]);

    $stmt = $conn -> prepare("SELECT * FROM managers WHERE username = ?");
    $stmt -> bind_param("s", $_SESSION["username"]);
    $stmt -> execute();
    $result = $stmt -> get_result();

    if( $result->num_rows > 0)
    {
        $row = $result -> fetch_assoc();
        if (password_verify($_POST["password"], $row["password"]))
        {
            header("Location: manage.php");
        }
        else
        {
            $_SESSION["attempts"] --;
        }
    }
    else
    {
        $_SESSION["attempts"] --;
    }
    }
}
else
{
    session_unset();
    session_destroy();
}
$page = "manager login";
include_once("header.php");
?>
<main class="thin-main">
    <h1>Manager Login</h1>
    <form action="manager_login.php" method="post">
        <fieldset>
            <legend>Details:</legend>
            <?php
            if($_SERVER["REQUEST_METHOD"] == "POST")
            {
                if ($_SESSION["attempts"] > 0)
                {
                    echo("<p>Authentication failed!. Either username or password is incorrect. " . $_SESSION["attempts"] . " attempts remaining</p>");
                }
                else
                {
                    echo("<p>No more attempts! you will have to wait a while before trying again</p>.");
                    $_SESSION["delay_start"] = time();
                }
            }
            ?>
            <label for="username" class="required">Username:</label>
            <input type="text" id="username" name="username">
            <label for="password" class="required">Password:</label>
            <input type="password" id="password" name="password">
            <input type="submit" class="form-buttons">
            <input type="reset" class="form-buttons">
        </fieldset>
    </form>
    <p>Don't have an account yet?<br>Register <a href="manager_registration.php" class="link">here</a>!</p>
</main>
<?php
include_once("footer.php");
?>