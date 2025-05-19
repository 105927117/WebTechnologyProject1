<?php
session_start();
require_once ('./settings.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
#sanitize user input to avoid code injection
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER["REQUEST_METHOD"]  == "POST") {
    #Collect and sanitize input
    $job_ref = clean_input($_POST["Job-reference-number"]);
    $first_name = clean_input($_POST["first-name"]);
    $last_name = clean_input($_POST["last-name"]);
    $dob = clean_input($_POST["Date-of-Birth"]);
    $gender = clean_input($_POST["Gender"]);
    $address = clean_input($_POST["Street-Address"]);
    $suburb = clean_input($_POST["Suburb"]);
    $state = clean_input($_POST["State"]);
    $postcode = clean_input($_POST["Postcode"]);
    $email = clean_input($_POST["Email"]);
    $phone = clean_input($_POST["Phone-number"]);
    $skills = "";
    if (isset($_POST["Other-skills"])){
        foreach ($_POST["skills"] as $skill) {
            $skills .= clean_input($skill) . "\n";
        } 
        if (in_array("Other", $_POST["skills"])) {
            if (empty($_POST["Other-skills"])) {
                die("Please specify the 'Other' skill.");
            }
        } else {
            if (!empty($_POST["Other-skills"])){
                die("You must check 'Other' to enter other skills.");
            }
        }
    }else{
        die("Please select at least one skill.");
    }
    #if other skills are not provided, set to null
    $other_skills = isset($_POST["Other-skills"]) ? clean_input($_POST["Other-skills"]) : "";
    #check if all required fields are filled
    if (empty($job_ref) || empty($first_name) || empty($last_name) || empty($dob) || empty($gender) || empty($address) || empty($suburb) || empty($state) || empty($postcode) || empty($email) || empty($phone) || empty($skills)) {
        echo "Error: Please fill in all required fields.";
        exit;
    }
    #Validate input fields
    if (!preg_match("/^[a-zA-Z\s]{1,20}$/", $first_name)) {
        die("Invalid first name. Only letters and spaces, max 20 characters.");
    }

    if (!preg_match("/^[a-zA-Z\s]{1,20}$/", $last_name)) {
        die("Invalid last name. Only letters and spaces, max 20 characters.");
    }

    if (!preg_match("/^[a-zA-Z0-9\s]{1,40}$/", $address)) {
        die("Invalid street address.");
    }

    if (!preg_match("/^[a-zA-Z\s]{1,40}$/", $suburb)) {
        die("Invalid suburb.");
    }

    if (!preg_match("/^\d{4}$/", $postcode)) {
        die("Invalid postcode. Must be exactly 4 digits.");
    }
    # State to postcode prefix mapping
    $state_postcode_map = [        # => operator is used inside arrays to assign a key to a value
        'VIC' => ['3', '8'], 
        'NSW' => ['1', '2'],
        'QLD' => ['4', '9'],
        'NT'  => ['0'],
        'WA'  => ['6'],
        'SA'  => ['5'],
        'TAS' => ['7'],
        'ACT' => ['0']
    ];

    $first_digit = substr($postcode, 0, 1);

    if (!in_array($first_digit, $state_postcode_map[$state])) {
        die("Postcode does not match the selected state.");
    }
    # use filter_var instead of preg_match because regex won’t cover all valid email formats
    # and filter_var is more efficient and safer than preg_match
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    } 

    if (!preg_match("/^[\d\s]{8,12}$/", $phone)) {
        die("Invalid phone number. Only digits and spaces, 8–12 characters.");
    }
    
    $query = "INSERT INTO eoi (
    `Jobrefnum`, 
    `firstname`, 
    `lastname`, 
    `DOB`, 
    `Gender`, 
    `StreetAddress`, 
    `Suburb`, 
    `State`, 
    `Postcode`, 
    `Email`, 
    `Phonenumber`, 
    `Skills`, 
    `Otherskills`)
    VALUES (
    '$job_ref', 
    '$first_name', 
    '$last_name', 
    '$dob', 
    '$gender', 
    '$address', 
    '$suburb', 
    '$state', 
    '$postcode', 
    '$email', 
    '$phone', 
    '$skills', 
    '$other_skills'
    )";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        # Create the table
        $create_table = "CREATE TABLE IF NOT EXISTS eoi (
            EOInumber INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
            `Jobrefnum` VARCHAR(20) NOT NULL,
            `firstname` VARCHAR(20) NOT NULL,
            `lastname` VARCHAR(20) NOT NULL,
            `DOB` DATE NOT NULL,
            `Gender` ENUM('Male','Female','Others') NOT NULL,
            `StreetAddress` VARCHAR(40) NOT NULL,
            `Suburb` VARCHAR(40) NOT NULL,
            `State` ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
            `Postcode` CHAR(4) NOT NULL,
            `Email` VARCHAR(100) NOT NULL,
            `Phonenumber` VARCHAR(20) NOT NULL,
            `Skills` TEXT NOT NULL,
            `Otherskills` TEXT DEFAULT NULL
            'status' ENUM('New','Current','Final') NOT NULL DEFAULT 'New'
        )";
        if (mysqli_query($conn, $create_table)){
            $result = mysqli_query($conn, $query);
        }else{
            echo "Error creating table: " . mysqli_error($conn);
        }
        if (!$result) {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }
    
    echo "Application submitted successfully. EOI number: " . mysqli_insert_id($conn);
    mysqli_close($conn);
} else {
    header("Location: ./apply.php");
    exit;
}
?>