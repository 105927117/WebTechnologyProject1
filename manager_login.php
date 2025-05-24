<?php
session_start();
include_once("settings.php");

 //the following function removes white space at the start and end of strings, removes the backslashes used as escape characters, and converts special characters into html format
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//set the number of attempts to 4 if it has not been set yet
if (!isset($_SESSION["attempts"]))
{
    $_SESSION["attempts"] = 4;
}
//set the delay start time to 0 if it has not been set yet
if (!isset($_SESSION["delay_start"]))
{
    $_SESSION["delay_start"] = 0;
}

//the following code executes once the user has submitted the form
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    //check if the current time is later than the start of the delay + 30 seconds
    if ($_SESSION["delay_start"] + 30 < time())
    {
    //keep track of the username
    $_SESSION["username"] = clean_input($_POST["username"]);
    
    //prepare the sql statement without attatching values to the username parameter
    $stmt = $conn -> prepare("SELECT * FROM managers WHERE username = ?");
    //bind the session username to the username parameter
    $stmt -> bind_param("s", $_SESSION["username"]);
    //run the query
    $stmt -> execute();
    //save the result
    $result = $stmt -> get_result();

    //check if the username exists in the database
    if( $result->num_rows > 0)
    {
        $row = $result -> fetch_assoc();
        //if the username matches the pasword go to the manage.php page, otherwise decrement the attempts
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
    //if the username doesnt exist decrement the attempts
    {
        $_SESSION["attempts"] --;
    }
    }
}
else
{
    //clear the session data if this page was accessed without post
    session_unset();
    session_destroy();
}

//specifying the page name allows it to appear in the title
$page = "manager login";
include_once("header.php");
?>
<main class="thin-main">
    <h1>Manager Login</h1>
    <!--form lets the user enter a username and password-->
    <form action="manager_login.php" method="post">
        <fieldset>
            <legend>Details:</legend>
            <?php
            //the following code displays the number of remaining attempts if the user has unsuccessfully logged in
            if($_SERVER["REQUEST_METHOD"] == "POST")
            {
                if ($_SESSION["attempts"] > 0)
                {
                    echo("<p>Authentication failed!. Either username or password is incorrect. " . $_SESSION["attempts"] . " attempts remaining</p>");
                }
                else
                {
                    echo("<p>No more attempts! you will have to wait a while before trying again</p>.");
                    $_SESSION["delay_start"] = time(); //this code begins the delay before the user can try to log in again
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
    <!--the user can visit this link to create an account-->
    <p>Don't have an account yet?<br>Register <a href="manager_registration.php" class="link">here</a>!</p>
</main>
<?php
include_once("footer.php");
?>