<!--Header of the page-->
<?php
$page = "apply";
include_once("header.php");

include_once("settings.php");

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

mysqli_close($conn);
?>

<main class="thin-main">
    <form action="process_eoi.php" method="post" novalidate>

        <section id="application-details">
            <!--Details of applicant-->
            <h3 id="application-details-header">Applicant Details</h3>
                <!--Names of applicant with 20 max alphabetical pattern with spaces allowed-->
                <label class="required name" for="first-name">First Name:</label><!--Add asterick(*) for required-->
                <input class="name" type="text" name="first-name" id="first-name" pattern="[a-zA-Z\s]{1,20}" title="maximum 20 alphabetical characters limit" placeholder="Ex: James" required>
                <label class="required name" for="last-name">Last Name</label>
                <input class="name" type="text" name="last-name" id="last-name" pattern="[a-zA-Z\s]{1,20}" title="maximum 20 alphabetical characters limit" placeholder="Ex: Bonds" required>
                <br>
                <!--Date of Birth that allow numerical inputs of slected input from calender-->
                <label class="required" for="Date-of-Birth">Date of Birth:</label>
                <input type="date" name="Date-of-Birth" id="Date-of-Birth" required>
                <br>
                <fieldset>
                    <!--Gender of applicant -->
                    <legend class="required">Gender:</legend>
                    <div class="gender-options">
                        <div class="radio-label">
                            <label for="Male">Male</label>
                            <input class="selection-box" type="radio" name="Gender" id="Male" value="Male" checked required>
                        </div>
                        <div class="radio-label">
                            <label for="Female">Female</label>
                            <input class="selection-box" type="radio" name="Gender" id="Female" value="Female">
                        </div>
                        <div class="radio-label">
                            <label for="Others">Others</label>
                            <input class="selection-box" type="radio" name="Gender" id="Others" value="Others">
                        </div>
                    </div>
                </fieldset>
        </section>

        <section id="contact-details">
            <!--Contact details of applicant-->
            <h3>Contact Details</h3>
                <!--Street address and suburb, required text input of alphabetical/numerical/spaces for 40 character max-->
                <label class="required" for="Street-Address">Street Address:</label>
                <br>
                <input type="text" name="Street-Address" id="Street-Address" pattern="[a-zA-Z0-9\s]{1,40}" title="maximum 40 alphabetical characters limit" placeholder="Ex: 13 John Street " required >
                <br>
                <label class="required" for="Suburb">Suburb:</label>
                <br>
                <input type="text" name="Suburb" id="Suburb" pattern="[a-zA-Z\s]{1,40}" title="maximum 40 alphabetical characters limit" placeholder="Ex: Hawthorn" required >
                <br>
                <!--State dropdown selection-->
                <label class="required" for="State">State:</label>
                <select name="State" id="State" required>
                    <option value="">Please Select</option>
                    <option value="VIC">VIC</option>
                    <option value="NSW">NSW</option>
                    <option value="QLD">QLD</option>
                    <option value="NT">NT</option>
                    <option value="WA">WA</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                </select>
                <!--Postcode, require numberical input of 4 digits-->
                <label class="required" for="Postcode">Postcode:</label>
                <input type="text" name="Postcode" id="Postcode" pattern="\d{4}" placeholder="Ex: 3000" required>
                <br>
                <!--Email using pattern for required input-->
                <label class="required" for="Email">Email:</label>
                <input type="text" name="Email" id="Email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" placeholder="Ex: you@example.com" required>
                <br>
                <!--Phone number, requied 8-12 digits input allowing spaces-->
                <label class="required" for="Phone-number">Phone number:</label>
                <input type="tel" name="Phone-number" id="Phone-number" pattern="[\d\s]{8,12}" placeholder="Ex: 0412 345 678" required>
        </section>
        <section id="job-application-details">
            <!--Appling job details-->
            <h3>Job Application Details</h3>
                <label class="required" for="Job-reference-number">Job reference number</label>
                <select name="Job-reference-number" id="Job-reference-number" required>
                    <option value="">Please select</option>
                    <?php
                    foreach ($jobs as $job) {
                        echo "<option value='{$job["reference_number"]}'>{$job["title"]}: {$job["reference_number"]}</option>";
                    }
                    ?>
                </select>
                <br>
                <label class="required" >Required technical list:</label>
                <?php
                foreach ($jobs as $job) {
                    $skills = $job["essential_qualifications"];
                    foreach (explode("\n", $skills) as $skill) {
                ?>
                <div class="selection-label">
                    <input class="selection-box" type="checkbox" name="skills[]" id="<?= $skill ?>" value="<?= $skill ?>">
                    <label for="<?= $skill ?>"><?= $skill ?></label>
                </div>
                <?php
                    }
                }
                ?>
                <div class="selection-label">
                <input class="selection-box" type="checkbox" name="skills[]" id="Other" value="Other">
                <label for="Other">Other.</label>
                </div>
                <textarea name="Other-skills" id="Other-skills" placeholder="Write down your other skills here...." rows="6" cols="25"></textarea>
        </section>


        <br>

        <input type="submit" value="Apply" id="submit" class="form-buttons" title="click to submit form">
        <input type="reset" value="Reset" id="reset" class="form-buttons" title="click to clear form">
    </form> 
</main>
<!--Footer of the page-->
<?php include_once("footer.php");?>