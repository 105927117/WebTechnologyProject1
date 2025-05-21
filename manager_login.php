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
if ($_SERVER["REQUEST_METHOD"] == "POST")
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
                echo("<p>Authentication failed!. Either username or password is incorrect!!!</p>");
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
    <p>Don't have an account yet?<br>Register <a href="manager_registration.php" class="link">here!</a></p>
</main>
<?php
include_once("footer.php");
?>