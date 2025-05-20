
<?php 
    $page = "manage";
    require_once("header.php");

?>
<main>
<h1>HR Manager - Manage EOIs</h1>
<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);

$page = "Manage";

require_once("settings.php");


// --- Handle Form Submissions ---


//if ($_SERVER["REQUEST_METHOD"]== 'POST') {
?>
    <h2>1. List All EOIs</h2>
    <form method="post">
        <button type="submit" name="list_all">Show All EOIs</button>
    </form>
<?php
    //  Listing  all EOIs
    if (isset($_POST['list_all'])) { // this line checks if the form is submitted by clicking the submit button named "list_all"
        $query = "SELECT * FROM eoi";
        $result = mysqli_query($conn,$query);
        $textmsg = 'All eoi listed below';

        if (mysqli_num_rows($result) > 0) {
            echo "<p>$textmsg</p>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr>
                    <th>EOInumber</th><th>Job Reference</th><th>First Name</th><th>Last Name</th><th>DOB</th>
                    <th>Gender</th><th>Street Address</th><th>Suburb</th><th>State</th><th>Postcode</th>
                    <th>Email</th><th>Phone Number</th><th>Skills</th><th>Other Skills</th><th>Status</th>
                  </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['EOInumber']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Jobrefnum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DOB']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['StreetAddress']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Suburb']) . "</td>";
                echo "<td>" . htmlspecialchars($row['State']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Postcode']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Phonenumber']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Skills']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Otherskills']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
           
        }else {
            echo "No records found!";
        }
    }

?>

<!-- 2. Search by Job Reference -->
<h2>2. List EOIs a Job</h2>
<form method="post">
     <input type="text" name="job_reference" required >
    <button type="submit" name="search_by_job">Search</button>
</form>

<?php

    // listing the Eoi's based on the job reference numbers 

    if (isset($_POST['search_by_job'])){
        $job_ref = mysqli_real_escape_string($conn,$_POST['job_reference']);   // this line gets the input from the html form which was asked to type the job_refernce number and  removes all the special charcaters
        $query = "SELECT * FROM eoi WHERE Jobrefnum = '$job_ref' ";  // gets the content from the eoi table according to the form input 
        $result = mysqli_query($conn,$query); // a function that is used to execute a query 
        $textmsg = "EOIs for job reference: $job_ref";


        if (mysqli_num_rows($result) > 0) {
            echo "<p>$textmsg</p>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr>
                    <th>EOInumber</th><th>Job Reference</th><th>First Name</th><th>Last Name</th><th>DOB</th>
                    <th>Gender</th><th>Street Address</th><th>Suburb</th><th>State</th><th>Postcode</th>
                    <th>Email</th><th>Phone Number</th><th>Skills</th><th>Other Skills</th><th>Status</th>
                  </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['EOInumber']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Jobrefnum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DOB']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['StreetAddress']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Suburb']) . "</td>";
                echo "<td>" . htmlspecialchars($row['State']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Postcode']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Phonenumber']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Skills']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Otherskills']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
        else {
            echo "No records found!";
        }
    
    }

 
?>

<!-- 3. Search by Applicant Name -->
<h2>3. Search EOIs by Applicant Name</h2>
<form method="post">
    First Name: <input type="text" name="firstname">
    Last Name: <input type="text" name="lastname">
    <button type="submit" name="search_by_name">Search</button>
</form>

<?php
    // listing the EOI based on the  the applicants name 

    if (isset($_POST['search_by_name'])) {
        $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
        $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
        $condition = [];
    
        if (!empty($first_name)) $condition[] = "firstname = '$first_name'";
        if (!empty($last_name))  $condition[] = "lastname = '$last_name'";
    
        if (!empty($condition)) {
            $sort = implode(" AND ", $condition);
            $query = "SELECT * FROM eoi WHERE $sort";
            $result = mysqli_query($conn, $query);
            $textmsg = "EOIs for applicant: $first_name $last_name";
    
            if (mysqli_num_rows($result) > 0) {
                echo "<p>$textmsg</p>";
                echo "<table border='1' cellpadding='5'>";
                echo "<tr>
                        <th>EOInumber</th><th>Job Reference</th><th>First Name</th><th>Last Name</th><th>DOB</th>
                        <th>Gender</th><th>Street Address</th><th>Suburb</th><th>State</th><th>Postcode</th>
                        <th>Email</th><th>Phone Number</th><th>Skills</th><th>Other Skills</th><th>Status</th>
                      </tr>";
    
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['EOInumber']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Jobrefnum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['DOB']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['StreetAddress']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Suburb']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['State']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Postcode']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Phonenumber']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Skills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Otherskills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
    
                echo "</table>";
            } else {
                echo "<p>No records found!</p>";
            }
        } else {
            echo "<p>Please enter a first name or last name.</p>";
        }
    }
?>
<!-- 4. Delete EOIs -->
<h2>4. Delete EOIs by Job Reference</h2>
<form method="post">
     <input type="text" name="job_ref" required>
    <button type="submit" name="delete_by_job">Delete EOIs</button>
</form>

<?php

    // deleting eoi by the job reference number 
    if (isset($_POST['delete_by_job'])){
        $job_ref = mysqli_real_escape_string($conn,$_POST['job_ref']);  // this line gets the input from the form and removes all the special charcaters
        $query = "DELETE FROM eoi WHERE Jobrefnum = '$job_ref' "; // sql query for deleting a value.
        $result = mysqli_query($conn,$query);  // runs the query to the database 
        $textmsg = "Deleted the EOI with JOB REFERNCE, $job_ref";  // text message that will appear on the webpage 


        if($result){
            echo "$textmsg";
        }

        else{
            echo "An error has occured.";
        }

    }

?>

<!-- 5. Update EOI Status -->
<h2>5. Update Status of an EOI</h2>
<form method="post">
    EOI ID: <input type="number" name="eoi_id" required>
    New Status: 
    <select name="status">
        <option value="New">New</option>
        <option value="Current">Current</option>
        <option value="Final">Final</option>
    </select>
    <button type="submit" name="update_status">Update</button>
</form>
<?php
    //  Updating EOI status 


    if (isset($_POST['update_status'])){   // this line checks if the form was submitted by clicking the update_status button on the html forms 
        $id = $_POST['eoi_id'];                 // this line stores the value of the input(integer) from the form named eoi_id from the variable named $id 
        $status = $_POST['status'];         // this line stores the value of the input(string) from the form named status from the variable named $id 
        $new_status = mysqli_real_escape_string($conn,$status);  
        $query = "UPDATE eoi set status = '$new_status' WHERE EOInumber = $id";
        
        
        if (mysqli_query($conn,$query)) // this line will check if the query is running in the database 
            echo "The status has been updated successfully";
        else 
            echo "The status has not been updated";

    }
        
    mysqli_close($conn);




?>








</main>


<?php
    require_once("footer.php");
?>