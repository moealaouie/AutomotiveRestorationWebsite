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
<h1 align="center">Contact us!</h1><br>
    <h4>Have questions? Want to request an estimate? Fill out this form and we'll get back to you in 1-2 business days. </h4><br><br>

<div class="grid">
    <div class="box" id="contact">
        <?php
        if(!empty ($status)) {
            if (!empty($status["error"])) {
                echo $status["error"];
            } else if (!empty($status["status"])) {
                echo $goodMsg;
            }
        }
        ?>
<form id="form" action="" method="post">
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
    <div class="box">
        <h2><b>Find us!</b><br>
            <a href="https://www.google.com/maps/dir//31242+Warren+Rd,+Westland,+MI+48185/@42.338445,-83.350193,14z/data=!4m8!4m7!1m0!1m5!1m1!1s0x883b4c962f1e8caf:0x1a38ed53a70daffd!2m2!1d-83.3501927!2d42.3384448?hl=en"><i class="fa fa-map-marker"></i>31242 Warren Rd<br>Westland, MI 48185</a>
            <br><br>
            <b>Call us!</b><br>313-555-6555
                <br><br>
            <b>Follow us!</b><br>
                    <a href="http://www.facebook.com"><i class="fa fa-facebook"></i></a>
                    <a href="http://www.instagram.com"><i class="fa fa-instagram"></i></a><br><br>
                <b>Hours</b><br>Mon-Fri: 8:00am-8:00pm<br>Sat-Sun: 10:00am-6:00pm<br>
        </h2>
    </div>
</div>

<div class="section" id="maps">

        <?php include("googlemaps.php"); ?>

    </div>
</div>

<?php require_once('footer.php'); ?>

    </body>
</html>
