<?php
$page = "enhancements";
include_once("header.php");
?>
<main>
    <h1>Enhancements</h1>
    <p><a href="manager_registration.php" class="form-buttons">Manager registration page</a></p>
    <p>Users can go onto this page to apply to become managers.</p>
    <p>to be eligible for becoming a manager, users must provide:</p>
    <ul>
        <li>A unique username</li>
        <li>A strong password containing:
            <ul>
                <li>At least 1 lowercase character</li>
                <li>At least 1 uppercase character</li>
                <li>At least 1 number</li>
                <li>At least 1 symbol</li>
            </ul>
        </li>
    </ul>
    <p><a href="manager_login.php" class="form-buttons">Manager login page</a></p>
    <p>Users can go onto this page to login to their newly created manager accounts.</p>
    <p>The page securely checks a username and password provided before the user, before granting access.</p>
    <p>If the user fails 4 login attempts, they will have to wait 30 seconds until they can try to login again (+ an extra 30 seconds for every attempt while they are locked out).</p>
</main>
<?php
include_once("footer.php");
?>