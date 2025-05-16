

<?php

$page = "Manage";





require_once("settings.php");


// --- Handle Form Submissions ---


$result = null;
$textmsg = '';




//  Listing  all EOIs
if (isset($_POST['list_all'])) {
    $query = "SELECT * FROM eoi";
    $result = mysqli_query($conn,$query);
    $textmsg = 'All eoi listed below';
}

// listing the Eoi's based on the job reference numbers 

if (isset($_POST['Search_by_job_reference'])){
    $job_ref = mysqli_real_escape_string($conn,$_POST['job_refernce']);   // this line gets the input from the html form which was asked to type the job_refernce number 
    $query = "SELECT * FROM eoi WHERE Job-reference-number == '$job_ref' ";  // gets the content from the eoi table according to the form input 
    $result = mysqli_query($conn,$query);
    $textmsg = "EOIs for job reference: $job_ref";
}

// listing the EOI based on the  the applicants name 

if (isset($_POST['Search_by_name'])){
    $first_name = mysqli_real_escape_string($conn,$_POST['firstname']);  // this line gets the input of the firstname from the form to check with the table to retrieve the data 
    $last_name = mysqli_real_escape_string($conn,$_POST['lastname']); // this line gets the input of the lastname from the form to check with the table to retrieve the data 
    $condition = [];
    if (!empty($first_name)) $condition[] = "first-name = '$first_name'"; // this line checks whether the $first_name variable is empty, if not a condition is enterd into an array $conditon as firstname is equal to  $first_name 
    if  (!empty($first_name)) $condition[] = "last-name = '$last_name'"; // this line checks whether the $first_name variable is empty, if not a condition is enterd into an array $conditon as lastname is equal to  $last_name 


    if (!empty($condition)) {
        $sort = implode("AND", $condition);
        $query = "SELECT * FROM eoi WHERE $sort";
        $result = mysqli_query($conn,$query);
        $textmsg = "Eoi for applicant , $first_name , $last_name ";
    } 
    else{
        $textmsg = "Please enter any name to search the data ";

    }

}




// deleting eoi by the job refernce number 
if (isset($_POST['delete_job_ref'])){
    $job_ref = mysqli_real_escape_string($conn,$_POST['job_ref']);
    $query = "DELETE FROM eoi WHERE Job-reference-number == '$job_ref' ";
    $result = mysqli_query($conn,$query);
    $textmsg = "Deleted the eoi with job refernce, $job_ref";




}


//  Updating EOI status 


if (isset($_POST['update_status'])){
    $id = $_POST['eoi_id'];
    $new_status = mysqli_real_escape_string($conn,$id);
    $query = "UPDATE eoi set status = $new_status WHERE EOInumber == $id";
    if (mysqli_query($conn,$query));

}
    
mysqli_close($conn);


require_once("header.php");

?>
<h1>HR Manager - Manage EOIs</h1>

// 1. List All EOIs 

<h2>1. List All EOIs</h2>
<form method="post">
    <button type="submit" name="list_all">Show All EOIs</button>
</form>

<!-- 2. Search by Job Reference -->
<h2>2. List EOIs a Job</h2>
<form method="post">
     <input type="text" name="job_reference" required >
    <button type="submit" name="search_by_job">Search</button>
</form>

<!-- 3. Search by Applicant Name -->
<h2>3. Search EOIs by Applicant Name</h2>
<form method="post">
    First Name: <input type="text" name="firstname">
    Last Name: <input type="text" name="lastname">
    <button type="submit" name="search_by_name">Search</button>
</form>

<!-- 4. Delete EOIs -->
<h2>4. Delete EOIs by Job Reference</h2>
<form method="post">
    Job Reference: <input type="text" name="job_reference_delete" required>
    <button type="submit" name="delete_by_job">Delete EOIs</button>
</form>

<!-- 5. Update EOI Status -->
<h2>5. Update Status of an EOI</h2>
<form method="post">
    EOI ID: <input type="number" name="eoi_id" required>
    New Status: 
    <select name="status">
        <option value="New">New</option>
        <option value="Interviewed">Interviewed</option>
        <option value="Hired">Hired</option>
        <option value="Rejected">Rejected</option>
    </select>
    <button type="submit" name="update_status">Update</button>
</form>

</body>


<?php
    require_once("footer.php");
?>