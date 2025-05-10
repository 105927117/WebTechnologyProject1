<!DOCTYPE html>
<html lang="en">
<head>
    <!--Meta tags for the page-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Alex Petrucci, Carl Petrucci, Kunvuth You, Mohamed Aarriz Mohamed Habeeb">
    <meta name="keywords" content="clickmaxxing, web development, webstites, programming, job descriptions, jobs, job applications">
    <meta name="description" content="View the job descriptions for the available jobs at ClickMaXXing.">
    <!--Title of the page which shows on the tab-->
    <title>ClickMaXXing | Jobs</title>
    <!--Link to styles-->
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <!--Header of the page-->
    <?php
    $page = "jobs";
    include_once("header.inc");
    ?>

    <!--Main content of the page-->
    <main>
        <!--Aside section which redirects user to applications page so that they can apply-->
        <aside>
            <h2>How Do I Apply?</h2>
            <p>We are hiring! To apply for one of our available jobs, just head over to our job applications page by clicking the 'Apply' link in the navagation bar at the top of the website, or the link below.</p>
            <!--Div below centers the link within it-->
            <div class="center-wrapper">
                <a href="apply.html" class="apply-now-button" title="click to apply for possition">
                    <p>Apply Now&nbsp;</p>
                    <!--SVG for button embeded in html so that the colour can be changed through css-->
                    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 91.94 113.74">
                        <path class="cls-1" d="M80.49,0H11.46C5.13,0,0,5.13,0,11.46v90.83c0,6.33,5.13,11.46,11.46,11.46h69.03c6.33,0,11.46-5.13,11.46-11.46V11.46c0-6.33-5.13-11.46-11.46-11.46ZM61.8,81.29H16.28c-2.38,0-4.3-1.97-4.3-4.41s1.93-4.41,4.3-4.41h45.52c2.38,0,4.3,1.97,4.3,4.41s-1.93,4.41-4.3,4.41ZM61.8,64.11H16.28c-2.38,0-4.3-1.97-4.3-4.41s1.93-4.41,4.3-4.41h45.52c2.38,0,4.3,1.97,4.3,4.41s-1.93,4.41-4.3,4.41ZM61.8,46.92H16.28c-2.38,0-4.3-1.97-4.3-4.41s1.93-4.41,4.3-4.41h45.52c2.38,0,4.3,1.97,4.3,4.41s-1.93,4.41-4.3,4.41ZM61.8,29.74H16.28c-2.38,0-4.3-1.97-4.3-4.41s1.93-4.41,4.3-4.41h45.52c2.38,0,4.3,1.97,4.3,4.41s-1.93,4.41-4.3,4.41ZM74.9,82.07c-2.8,0-5.07-2.32-5.07-5.19s2.27-5.19,5.07-5.19,5.07,2.32,5.07,5.19-2.27,5.19-5.07,5.19ZM74.9,64.89c-2.8,0-5.07-2.32-5.07-5.19s2.27-5.19,5.07-5.19,5.07,2.32,5.07,5.19-2.27,5.19-5.07,5.19ZM74.9,47.7c-2.8,0-5.07-2.32-5.07-5.19s2.27-5.19,5.07-5.19,5.07,2.32,5.07,5.19-2.27,5.19-5.07,5.19ZM74.9,30.52c-2.8,0-5.07-2.32-5.07-5.19s2.27-5.19,5.07-5.19,5.07,2.32,5.07,5.19-2.27,5.19-5.07,5.19Z"/>
                    </svg>
                </a>
            </div>
        </aside>

        <?php
            $host = "localhost";
            $user = "root";
            $pwd = "";
            $sql_db = "clickmaxxing_db";

            $conn = mysqli_connect($host, $user, $pwd, $sql_db);
            if (!$conn) {
                die("Connection failed: " . mysql_connect_error());
            }

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
        <h1>Job Descriptions</h1>
        <!--Section for giving users an overview of the company-->
        <section>
            <h2>Company Description</h2>
            <!--Content below taken from home page which used Gen AI for this section-->
            <p>ClickMaXXing, we are committed to driving innovation and delivering high-quality IT services to businesses worldwide. Our team of skilled professionals specializes in software development, cybersecurity, cloud computing, AI, and IT consulting. We believe in fostering a dynamic and inclusive work environment that encourages creativity and career growth.</p>
        </section>
        <br>
        <!--Section which contains cards whith an overview of each position description-->
        <section>
            <h2>Positions</h2>
            <!--Div below puts the cards in a flexbox so that they can adjust their positions for different screen sizes-->
            <div class="card-wrapper">
                <?php
                    foreach ($jobs as $job) {
                ?>
                    <article class="description-card">
                        <h2><?= $job["title"] ?></h2>
                        <p>Reference No. <?= $job["reference_number"] ?></p>
                        <img src="<?= $job["summary_image"] ?>" alt="<?= $job["summary_alt"] ?>">
                        <p><?= $job["summary"] ?></p>
                        <a href="#<?= str_replace(" ", "-", $job["title"]) ?>" title="click to find out more about this position">More Info...</a>
                    </article>
                <?php
                    }
                ?>
            </div>
        </section>
        <?php
            foreach ($jobs as $job) {
        ?>
            <hr>
            <div id="<?= str_replace(" ", "-", $job["title"]) ?>" class="long-description">
                <img src="<?= $job["description_image"] ?>" alt="<?= $job["description_alt"] ?>" class="description-img">
                <section class="detailed-description">
                    <h2><?= $job["title"] ?></h2>
                    <p>Reference No. <?= $job["reference_number"] ?></p>
                    <ul>
                        <li><strong>Description:</strong> <?= $job["description"] ?></li>
                        <li><strong>Salary:</strong> <?php
                            $min = ($job["salary_min"] / 1000);
                            $max = ($job["salary_max"] / 1000);
                            echo "\${$min}K-\${$max}K";
                        ?></li>
                        <li><strong>Report to:</strong> <?= $job["report_to"] ?></li>
                        <li>
                            <strong>Key Responsibilities:</strong>
                            <ol>
                                <?php
                                    foreach (explode("\n", $job["responsibilities"]) as $item) {
                                        echo "<li>$item</li>";
                                    }
                                ?>
                            </ol>
                        </li>
                        <li>
                            <strong>Qualifications, Skills, Knowledge and Attributes:</strong>
                            <dl>
                                <dt><strong>Essential</strong></dt>
                                <dd>
                                    <ol>
                                        <?php
                                            foreach (explode("\n", $job["essential_qualifications"]) as $item) {
                                                echo "<li>$item</li>";
                                            }
                                        ?>
                                    </ol>
                                </dd>
                                <dt><strong>Preferred</strong></dt>
                                <dd>
                                    <ol>
                                        <?php
                                            foreach (explode("\n", $job["preferred_qualifications"]) as $item) {
                                                echo "<li>$item</li>";
                                            }
                                        ?>
                                    </ol>
                                </dd>
                            </dl>
                        </li>
                    </ul>
                </section>
            </div>
        <?php
            }
        ?>
    </main>
    <!--Footer of the page-->
    <?php include_once("footer.inc");?>
</body>
</html>