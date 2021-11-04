<?php include('session.php');

//validate data from
function validateData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// check if contact form was submitted
if ( isset($_POST['action']) && $_POST['action'] == 'contact') {

    $fullName = validateData($_POST['name']);
    $email = validateData($_POST['email']);
    $phone = validateData($_POST['phone']);
    $make = validateData($_POST['make']);
    $model = validateData($_POST['model']);
    $year = validateData($_POST['year']);
    $concerns= validateData($_POST['concerns']);
    $safe = true;
    $errMsg = "";
    $goodMsg = "";

    if (!is_string($fullName)) {
        $safe = false;
        $errMsg = "Invalid Name, Please try again.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        $safe = false;
        $errMsg = "Invalid E-mail, Please try again.";
    }

    $phone = str_replace("-", "", $phone);
    $phone = str_replace("(", "", $phone);
    $phone = str_replace(")", "", $phone);
    if (!is_string($phone) || empty($phone) || !is_numeric($phone)) {
        $safe = false;
        $errMsg = "Invalid Phone number, Please try again.";
    }

    if (!is_string($make) || empty($make)) {
        $safe = false;
        $errMsg = "Invalid make, Please try again.";
    }

    if (!is_string($model) || empty($model)) {
        $safe = false;
        $errMsg = "Invalid model, Please try again.";
    }

    if (!is_string($year) || empty($year) || !is_numeric($year)) {
        $safe = false;
        $errMsg = "Invalid year, Please try again.";
    }

    if (!is_string($concerns) || empty($concerns)) {
        $safe = false;
        $errMsg = "Invalid concerns/description, Please try again.";
    }

    if ($safe) {
        //building user info array
        $values = array(
                'contactName' => $fullName,
                'contactEmail' => $email,
                'contactPhone' => $phone,
                'contactCarmake' => $make,
                'contactCarmodel' => $model,
                'contactCaryear' => $year,
                'contactConcerns' => $concerns
        );

        //process request
        $status = $database->processRequest("contact", $values);

        //needs contact api, also do i want to store in a session?
        //is this okay logic?
        if (empty($status["error"])) // 0 = empty
        {
            $goodMsg = "Thank you, we will get back to you as soon as possible.";
        }
        else if(!empty($contact_info['error'])){
            // Return an error message
            $errMsg = $contact_info['error'];
        }
        else
        {
            // Return a really bad error message
            $errMsg = 'Something unexpected happened?';
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">

    <?php require_once('head.php'); ?>

    <body>

        <?php require_once('header.php'); ?>

        <div class="main">
            <div class="section slideshow-container" id="s1" >
                <div class="slides fade">
                    <img src="media/image1.jpg" class="slideshow-img" alt="Classic Mustang">
                    <div class="caption-text"></div>
                </div>
                <div class="slides fade">
                    <img src="media/image2.jpg" class="slideshow-img" alt="Classic Corvette">
                    <div class="caption-text"></div>
                </div>
                <div class="slides fade">
                    <img src="media/car_three.JPG" class="slideshow-img" alt="Classic Corvette">
                    <div class="caption-text"></div>
                </div>
                <div class="slides fade">
                    <img src="media/car_two.JPG" class="slideshow-img" alt="Classic Mustang">
                    <div class="caption-text"></div>
                </div>
                <div class="section" id="s3">
                    <br><br><br>
                    <h1>The Limelight Difference</h1>
                    <br>
                    <p id=whyUs> We first started our little shop in 1978 and it overtime it grew into this art of restoring classic cars in Southeastern Michigan. Limelight Restoration is committed to making your dream customization a reality. Our designs are modern and classic. Whether its a vintage paint job or you are shopping for a trustworthy company to maintain your beloved vehicle, we can get the job done. Every car is crafted to your satisfaction. New features include a modern transmission, steering, suspension, and brakes. We are your one stop shop for your classic car needs.     </p>
                </div>
                <div class="section" id="s2">
                <h1>Our Services</h1>
                <div class="grid">
                    <div class="box" id="b1"><h2>Body Work</h2>
                        <ul>
                            <li>Rust and dent repair</li>
                            <li>Paint </li>
                            <li>Bumper replacement</li>
                            <li>Body panels </li>
                            <li>Upgrades to fuel injection </li>
                        </ul></div>
                    <div class="box" id="b2"><h2>Restoration</h2>
                        <ul>
                            <li>Interior</li>
                            <li>Frame off</li>
                            <li>Engine repair </li>
                            <li>Headers</li>
                            <li>Breaks </li>
                        </ul></div>
                    <div class="box" id="b3"><h2>Miscellaneous</h2>
                        <ul>
                            <li>Vehicle appraisals</li>
                            <li>Detailing services </li>
                            <li>Fluid check</li>
                            <li>Oil and filter changes</li>
                            <li>Window tinting </li>
                        </ul></div>
                </div>
                    <br><br><br>
            </div>
            <div class="section" id="s4">
                <div class="grid">
                    <div class="box" id="maps">
                        <?php include("googlemaps.php"); ?>
                    </div>
                    <div class="box" id="contact">
                        <h2>Contact Us</h2>
                        <?php
                            if(!empty ($status)) {
                                if (!empty($status["error"])) {
                                    echo $status["error"];
                                } else if (!empty($status["status"])) {
                                    echo $goodMsg;
                                }
                            }
                        ?>
                        <form id="form" action="" method="POST">
                            <label for="name">Name:</label><br>
                            <input type="text" id="name" name="name" placeholder="Your Name"><br>

                            <label for="email">Email:</label><br>
                            <input type="text" id="email" name="email" placeholder="Your email"><br>

                            <label for="phone">Phone:</label><br>
                            <input type="text" id="phone" name="phone" placeholder="Phone Number"><br>

                            <label for="make">Car Make:</label><br>
                            <input type="text" id="make" name="make" placeholder="Car make"><br>

                            <label for="model">Car Model:</label><br>
                            <input type="text" id="model" name="model" placeholder="Car Model"><br>

                            <label for="year">Car Year:</label><br>
                            <input type="text" id="year" name="year" placeholder="Car Year"><br>

                            <input type="hidden" name="action" value="contact">

                            <label for="concerns">Description/Concerns:</label><br>
                            <textarea id="concerns" name="concerns" rows="6" cols="50"></textarea><br>

                            <label for="submit">
                            <input type="submit" id="submit"></label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            <?php require_once('footer.php'); ?>

    </body>
    <script src="slideshow.js"></script>
</html>







