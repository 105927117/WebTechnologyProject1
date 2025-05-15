 <!--Header of the page-->
<?php
$page = "home";
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
    <main id="maincontent" >

        <!-- Subheading -->
         <aside id="sub_head">
            <h2>Discover what is right for you</h2>
        </aside>
            <h1>Welcome to ClickMaXXing</h1>
        

        <!--content from GeN AI-->
        <div id="descriptionn">
            <p><em>ClickMaXXing, we are committed to driving innovation and delivering high-quality IT services to businesses worldwide.
            Our team of skilled professionals specializes in software development, cybersecurity, cloud computing, AI, and IT consulting.
            We believe in fostering a dynamic and inclusive work environment that encourages creativity and career growth.</em></p>
        </div>

            <!-- Subheading -->
        <section id="abt_company">        <!--created section tags for contents to group them -->
            <h2>About ClickMaXXing</h2>
            <p>Australia's one of the newest and largest IT network, providing solutions to more than 100 countries around the world.</p>
            <img src="images/about_us.jpeg" alt="our company's performance over the Years"  class="about-img" > <!--image took from google-->
            
        </section>

            <!-- Subheading -->
        <h2>Our Services</h2>
        <!--content from Gen AI-->
        <p>
            <ul>          <!--created unordered lists tags to create a list-->
                <li>✔ Software Development - Custom web and mobile applications tailored to business needs.</li>
                <li>✔ Cybersecurity Solutions - Advanced security measures to protect businesses from cyber threats.</li>
                <li>✔ Cloud Computing - Scalable cloud solutions for storage and infrastructure.</li>
                <li>✔ AI & Machine Learning - Intelligent automation and predictive analytics.</li>
                <li>✔ IT Consulting - Expert guidance to optimize technology strategies.</li>       <!--used list tag to enlist items within the lists-->
            </ul>

            <hr>
             <!--content from Gen AI-->

        <h1>Join our Team</h1>           

        <section id="join_team">
            <div class="join_team_content">
                <p>We are always looking for talented professionals to join our growing team. <br>
                    If you are passionate about technology and innovation, check out our latest job openings below.</p>
                        
                
                <h2> Current Job Openings </h2>
                    <!--content from Gen AI-->
                <ol>
                    <?php foreach($jobs as $job): ?>
                        <li>
                            <p><?= $job["title"] ?></p>
                            <ul>
                                <li>Overview: <?= $job["summary"] ?></li>
                                <li>
                                    Requirements:
                                    <ul>
                                        <?php foreach(explode("\n", $job["essential_qualifications"]) as $requirement): ?>
                                        <li><?= $requirement ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php endforeach ?>
                </ol>
                        <p>For more details click the below link</p>
                        <a href="jobs.php" title="click to see current vacancies">More Details</a>             <!--used anchor tags for providing direct access to the jobs page for more details -->
            </div>

        </section>
        <hr>   <!--hr tags for horizontal lines-->
        
        <!--content from Gen AI-->
        <section id="closure">
            <h2>Why Work with us?</h2>       <!--used image tag for adding image to the section-->
            <img src="images/whychoosee.jpeg" alt="image describes about why choosing ClickMaXXing" class="whychoosee">
            <ul>                                                 <!--created unordered lists tags to create a list-->
                <li>✅ Competitive Salaries</li>
                <li>✅ Flexible Work Environment</li>
                <li>✅ Learning & Growth Opportunities</li>     <!--used list tag to enlist items within the lists-->
                <li>✅ Inclusive & Diverse Culture</li>
                <li>✅ Cutting-Edge Technologies</li>
            </ul>
        </section>


    </main>             
<!--Footer of the page-->
<?php include_once("footer.php");?>