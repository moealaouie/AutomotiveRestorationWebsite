<?php include('session.php');

function validateData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ( isset($_POST['action']) && $_POST['action'] == 'createAcc') {

    //getting data from createAccount
    $firstName = validateData($_POST['firstName']);
    $lastName = validateData($_POST['lastName']);
    $email = validateData($_POST['email']);
    $phone = validateData($_POST['phone']);
    $address = validateData($_POST['address']);
    $city = validateData($_POST['city']);
    $role = "Customer";
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $user = validateData($_POST['username']);
    $state = validateData($_POST['state']);
    $zip = validateData($_POST['zip']);
    $employeeHours = 0;
    $billingRate = "0";
    //creating flag variables
    $safe = true;
    $errMsg = "";

    //sanitizing data **Needs City added eventually
    if (!is_string($firstName)) {
        $safe = false;
        $errMsg = "Invalid First Name, Please try again.";
    }

    if (!is_string($lastName)) {
        $safe = false;
        $errMsg = "Invalid Last Name, Please try again.";
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

    if (!is_string($address) || empty($address)) {
        $safe = false;
        $errMsg = "Invalid Address, Please try again.";
    }

    if (!is_string($city) || empty($city)) {
        $safe = false;
        $errMsg = "Invalid City, Please try again.";
    }

    if (!is_string($user) || empty($user)) {
        $safe = false;
        $errMsg = "Invalid User, Please try again.";
    }

    if (!is_string($state) || empty($state)) {
        $safe = false;
        $errMsg = "Invalid State, Please try again.";
    }

    if (!is_string($zip) || empty($zip)){
        $safe = false;
        $errMsg = "Invalid Zip code, Please try again.";
    }

    //check if passwords match and are strings.
    if ($pass !== $cpass || !is_string($pass) || empty($pass) || empty($cpass)){
        $safe = false;
        $errMsg = "Invalid Password, Enter valid data and make sure your passwords match!";
    }

    //checking password for Uppercase, Lowercase & a number
    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z]{8,24}$/',$pass)){
        $safe = false;
        $errMsg = "Invalid Password, Your password must meet our criteria!";
    }

    if ($safe) {
        //building user info array
        $values = array(
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'role' => $role,
                'password' => $pass,
                'username' => $user,
                'city' => $city,
                'state' => $state,
                'zipcode' => $zip,
                'employeeHours' => $employeeHours,
                'billingRate' => $billingRate
        );

        //process request
        $user_info = $database->processRequest("signup", $values);

        if (!empty($user_info["userID"])) // 0 = empty
        {
            // Storing session data
            $_SESSION["user_info"] = $user_info;

            // Redirecting to homepage
            header("location: index.php");
            exit();

        }
        else if(!empty($user_info['error'])){
            // Return an error message
            $errMsg = $user_info['error'];
        }
        else
        {
            // Return an error message
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
    <h1>Create Account</h1>

    <?php if(!empty($errMsg)){echo $errMsg;} ?>


    <form method="post" id="form" action="">
        <fieldset>
            <label for="firstName">First Name:</label><br>
            <input type="text" id="firstName" name="firstName" placeholder="Bob" value="<?php if(isset($_POST['firstName'])){echo $_POST['firstName'];};?>"><br>

            <label for="lastName">Last Name:</label><br>
            <input type="text" id="lastName" name="lastName" placeholder="Smith" value="<?php if(isset($_POST['lastName'])){echo $_POST['lastName'];};?>"><br>

            <label for="address">Address:</label><br>
            <input type="text" id="address" name="address" placeholder="1234 Main st." value="<?php if(isset($_POST['address'])){echo $_POST['address'];};?>"><br>

            <label for="city">City:</label><br>
            <input type="text" id="city" name="city" placeholder="City" value="<?php if(isset($_POST['city'])){echo $_POST['city'];};?>"><br>

            <label for="state">State:</label><br>
            <select id="state" name="state">
                <option value="<?php if(isset($_POST['city'])){echo $_POST['city'];};?>"><?php if(isset($_POST['city'])){echo $_POST['city'];};?></option>
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AR">AR</option>
                <option value="AZ">AZ</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DC">DC</option>
                <option value="DE">DE</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="IA">IA</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="MA">MA</option>
                <option value="MD">MD</option>
                <option value="ME">ME</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MO">MO</option>
                <option value="MS">MS</option>
                <option value="MT">MT</option>
                <option value="NC">NC</option>
                <option value="NE">NE</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NV">NV</option>
                <option value="NY">NY</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WI">WI</option>
                <option value="WV">WV</option>
                <option value="WY">WY</option>
            </select><br>

            <label for="zip">Zip Code:</label><br>
            <input type="text" id="zip" name="zip" placeholder="98765" maxlength="9" value="<?php if(isset($_POST['zip'])){echo $_POST['zip'];};?>"><br>

            <label for="email">Email:</label><br>
            <input type="text" id="email" name="email" placeholder="abc@example.com" value="<?php if(isset($_POST['email'])){echo $_POST['email'];};?>"><br>

            <label for="phone">Phone:</label><br>
            <input type="text" id="phone" name="phone" placeholder="999-123-4567" maxlength="14" value="<?php if(isset($_POST['phone'])){echo $_POST['phone'];};?>"><br>

            <input type="hidden" name="action" value="createAcc">
        </fieldset>
            <br>
        <fieldset>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?php if(isset($_POST['username'])){echo $_POST['username'];};?>"><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br><br>

            <label for="cpassword">Confirm Password:</label><br>
            <input type="password" id="cpassword" name="cpassword"><br><br>

            <ul class="passList">
                <li>Password must be at least 8 characters</li>
                <li>Must contain combination of lowercase and UPPERCASE</li>
                <li>Must contain at least 1 number</li>
            </ul>
        </fieldset><br>
            <label for="submit">
            <input type="submit" id="submit"></label>
    </form>


</div>