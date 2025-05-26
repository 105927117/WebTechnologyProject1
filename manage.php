
<?php 
    //specifying the page name allows it to appear in the title
    $page = "manage";
    session_start();
    if (!isset($_SESSION["username"])) {
        header("Location: manager_login.php");
        exit;
    }
    require_once("header.php");
?>
<main>

<p>Actions:</p>
<ul>
    <li><a class="link" href="#eoi-management">Manage EOIs</a></li>
    <li><a class="link" href="#approve-managers">Approve Managers</a></li>
    <li><a class="link" href="logout.php">Logout</a></li>
</ul>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$page = "Manage";

require_once("settings.php");

// Get all the jobs to fill in the filter options
$sql = "SELECT * FROM jobs";
$result = mysqli_query($conn, $sql);
$jobs = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
} else {
    echo "Query failed.";
}
?>

<h1 id="eoi-management">EOI Management</h1>
<div class="eoi-management-forms">
    <div class="manage-eoi-form">
        <h2>View</h2>
        <form method="post">
            <label for="sort">Sort by:</label>
            <select id="sort" name="sort">
                <!--the php code inside of each option is just there to ensure that when the page is reloaded via post, the correct option is still selected-->
                <option value="EOInumber" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "EOInumber") {echo("selected='selected'");} ?>>EOI number</option>
                <option value="Jobrefnum" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "Jobrefnum") {echo("selected='selected'");} ?>>Job reference</option>
                <option value="firstname" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "firstname") {echo("selected='selected'");} ?>>First name</option>
                <option value="lastname" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "lastname") {echo("selected='selected'");} ?>>Last name</option>
                <option value="DOB" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "DOB") {echo("selected='selected'");} ?>>DOB</option>
                <option value="Gender" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "Gender") {echo("selected='selected'");} ?>>Gender</option>
                <option value="StreetAddress" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "StreedAddress") {echo("selected='selected'");} ?>>Street Address</option>
                <option value="Suburb" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "Suburb") {echo("selected='selected'");} ?>>Suburb</option>
                <option value="State" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "State") {echo("selected='selected'");} ?>>State</option>
                <option value="Postcode" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "Postcode") {echo("selected='selected'");} ?>>Postcode</option>
                <option value="Email" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "Email") {echo("selected='selected'");} ?>>Email</option>
                <option value="Phonenumber" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "Phonenumber") {echo("selected='selected'");} ?>>Phone number</option>
                <option value="status" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "status") {echo("selected='selected'");} ?>>Status</option>
            </select>
            <label for="filter">Filter by:</label>
            <select id="filter" name="filter">
                <!--the php code inside of each option is just there to ensure that when the page is reloaded via post, the correct option is still selected-->
                <option value="none" <?php if (isset($_POST["filter"]) && $_POST["filter"] == "none") {echo("selected='selected'");} ?>>None</option>
                <option value="name" <?php if (isset($_POST["filter"]) && $_POST["filter"] == "name") {echo("selected='selected'");} ?>>Name</option>
                <option value="job" <?php if (isset($_POST["filter"]) && $_POST["filter"] == "job") {echo("selected='selected'");} ?>>Job</option>
            </select>
            <div id="filter-name">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first_name">
                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last_name">
            </div>
            <div id="filter-job">
                <label for="job">Job:</label>
                <select name="job" id="job">
                    <?php foreach($jobs as $job): ?>
                        <option value="<?= $job["reference_number"]?>"><?= $job["title"]?>: <?= $job["reference_number"]?></option>
                    <?php endforeach ?>
                </select>
            </div>
    
            <button type="submit" class="form-buttons">Refresh</button>
        </form>
    </div>
    
    <div class="manage-eoi-form">
        <h2>Modify</h2>
        <form method="post">
            <label for="selection">Select With:</label>
            <select id="selection" name="selection">
                <option value="eoi_number">EOI Number</option>
                <option value="reference_number">Job Reference Number</option>
            </select>
            <div id="select-from-eoi">
                <label for="eoi-num">EOI Number:</label>
                <input type="number" name="eoi_num" id="eoi-num">
            </div>
            <div id="select-from-refnum">
                <label for="refnum">Reference Number:</label>
                <select name="refnum" id="refnum">
                    <?php foreach($jobs as $job): ?>
                        <option value="<?= $job["reference_number"]?>"><?= $job["title"]?>: <?= $job["reference_number"]?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <label for="modification">Modification:</label>
            <select name="modification" id="modification">
                <option value="delete">Delete</option>
                <option value="status">Change Status</option>
            </select>
            <div id="status-selection">
                <label for="change-status">Status:</label>
                <select name="change_status" id="change-status">
                    <option value="New">New</option>
                    <option value="Current">Current</option>
                    <option value="Final">Final</option>
                </select>
            </div>
            <button type="submit" name="modify" class="form-buttons">Modify EOIs</button>
        </form>
    </div>
</div>

<br>

<?php
// If user has pressed the approve managers button
if (isset($_POST["approve"])) {
    foreach ($_POST["approved_managers"] as $manager) {
        $stmt = $conn->prepare("UPDATE managers SET approved=1 WHERE username=?;");
        $stmt->bind_param("s", $manager);
        $stmt->execute();
    }
}

// If user has pressed the modify button
if (isset($_POST["modify"])) {
    if ($_POST["selection"] == "eoi_number") {
        $where = " WHERE EOInumber=?;";
        $bind = $_POST["eoi_num"];
    }
    if ($_POST["selection"] == "reference_number") {
        $where = " WHERE Jobrefnum=?;";
        $bind = $_POST["refnum"];
    }
    if ($_POST["modification"] == "delete") {
        $stmt = $conn->prepare("DELETE FROM eoi" . $where);
        $stmt->bind_param("s", $bind);
    }
    if ($_POST["modification"] == "status") {
        $stmt = $conn->prepare("UPDATE eoi set status = ?" . $where);
        $stmt->bind_param("ss", $_POST["change_status"], $bind);
    }
    $stmt->execute();
}

// Sort by EOInumber if no option has been set
if(!isset($_POST["sort"])) {
    $_POST["sort"] = "EOInumber";
}

// Filter by none if no option has been set
if (!isset($_POST["filter"])) {
    $_POST["filter"] = "none";
}

// Prepare a different sql statement based on the filter option chosen
switch ($_POST["filter"]) {
    case "none":
        $stmt = $conn->prepare("SELECT * FROM eoi ORDER BY " . $_POST["sort"] . " ASC;");
        break;

    case "name":
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        if ($first_name == "" && $last_name == "") { 
            $stmt = $conn->prepare("SELECT * FROM eoi ORDER BY " . $_POST["sort"] . " ASC;");
            break;
        }
        if ($first_name == "") {    
            $stmt = $conn->prepare("SELECT * FROM eoi WHERE lastname=? ORDER BY " . $_POST["sort"] . " ASC;");
            $stmt->bind_param("s", $last_name);
            break;
        }
        if ($last_name == "") {
            $stmt = $conn->prepare("SELECT * FROM eoi WHERE firstname=? ORDER BY " . $_POST["sort"] . " ASC;");
            $stmt->bind_param("s", $first_name);
            break;
        }
        $stmt = $conn->prepare("SELECT * FROM eoi WHERE firstname=? AND lastname=? ORDER BY " . $_POST["sort"] . " ASC;");
        $stmt->bind_param("ss", $first_name, $last_name);
        break;

    case "job":
        $stmt = $conn->prepare("SELECT * FROM eoi WHERE Jobrefnum=? ORDER BY " . $_POST["sort"] . " ASC;");
        $stmt->bind_param("s", $_POST["job"]);
        break;
}
//  Listing  all EOIs
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='5' class='resize-table'>";
    echo "<tr>
            <th>EOInumber</th><th>Job Reference</th><th>First Name</th><th>Last Name</th><th>DOB</th>
            <th>Gender</th><th>Street Address</th><th>Suburb</th><th>State</th><th>Postcode</th>
            <th>Email</th><th>Phone Number</th><th>Skills</th><th>Other Skills</th><th>Status</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['EOInumber'] . "</td>";
        echo "<td>" . $row['Jobrefnum'] . "</td>";
        echo "<td>" . $row['firstname'] . "</td>";
        echo "<td>" . $row['lastname'] . "</td>";
        echo "<td>" . $row['DOB'] . "</td>";
        echo "<td>" . $row['Gender'] . "</td>";
        echo "<td>" . $row['StreetAddress'] . "</td>";
        echo "<td>" . $row['Suburb'] . "</td>";
        echo "<td>" . $row['State'] . "</td>";
        echo "<td>" . $row['Postcode'] . "</td>";
        echo "<td>" . $row['Email'] . "</td>";
        echo "<td>" . $row['Phonenumber'] . "</td>";
    
        echo "<td><ul>";
        foreach (explode("\n", trim($row["Skills"])) as $skill) {
            echo "<li>" . $skill . "</li>";
        }
        echo "</ul></td>";

        echo "<td>" . $row['Otherskills'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    
}else {
    echo "No records found!";
}
?>

<h1 id="approve-managers">Approve Managers</h1>
<form method="post">
    <button type="submit" class="form-buttons" name="approve">Approve Selected</button>
    <br>
    <br>
    <?php
    $query = "SELECT * FROM managers WHERE approved=0";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if ($result->num_rows == 0) {
            echo "<p>All manager requests have been approved.</p>";
        } else {
            echo "<table class='resize-table'>";
            echo "<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Approve</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["username"] . "</td>";
                echo "<td>" . $row["first_name"] . "</td>";
                echo "<td>" . $row["last_name"] . "</td>";
                echo "<td><input type='checkbox' name='approved_managers[]' value='" . $row["username"] . "'></td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "Query Error!";
    }
?>
</form>
</main>
<?php
    mysqli_close($conn);
    require_once("footer.php");
?>