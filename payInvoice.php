<?php

    include('session.php');

    if(isset($_GET['id'])){
        $values = array(
            'userID' => null,
            'invoiceID' => $_GET['id'],
        );
        $invoice = $database->processRequest("getInvoice", $values);

        $values = array(
            'jobID' => $invoice['0']['jobID'],
        );
        $balance = $database->processRequest("getBalance", $values);
    }

    function validateData($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST['action']) && $_POST['action'] == 'payment') {
        //getting data from payment form
        $billingName = validateData($_POST['billingName']);
        $billingEmail = validateData($_POST['billingEmail']);
        $billingAddress = validateData($_POST['billingAddress']);
        $billingCity = validateData($_POST['billingCity']);
        $billingState = validateData($_POST['billingState']);
        $billingZip = validateData($_POST['billingZip']);
        $billingPhone = validateData($_POST['billingPhone']);
        $cardName = validateData($_POST['cardName']);
        $cardNumber = validateData($_POST['cardNumber']);
        $expMonth = validateData($_POST['expMonth']);
        $expYear = validateData($_POST['expYear']);
        $cvv = validateData($_POST['cvv']);
        $payment = validateData($_POST['payment']);
        $safe = true;

        if (!is_string($billingName) || empty($billingName)) {
            $safe = false;
            $errMsg = "Invalid Name, Please try again.";
        }

        if (!filter_var($billingEmail, FILTER_VALIDATE_EMAIL) || empty($billingEmail)) {
            $safe = false;
            $errMsg = "Invalid E-mail, Please try again.";
        }

        if (!is_string($billingAddress) || empty($billingAddress)) {
            $safe = false;
            $errMsg = "Invalid Address, Please try again.";
        }

        if (!is_string($billingCity) || empty($billingCity)) {
            $safe = false;
            $errMsg = "Invalid City, Please try again.";
        }

        if (!is_string($billingState) || empty($billingState)) {
            $safe = false;
            $errMsg = "Invalid State, Please try again.";
        }

        if (!is_string($billingZip) || empty($billingZip) || !is_numeric($billingZip)) {
            $safe = false;
            $errMsg = "Invalid Zip, Please try again.";
        }

        $billingPhone = str_replace("-", "", $billingPhone);
        $billingPhone = str_replace("(", "", $billingPhone);
        $billingPhone = str_replace(")", "", $billingPhone);
        if (!is_string($billingPhone) || empty($billingPhone) || !is_numeric($billingPhone)) {
            $safe = false;
            $errMsg = "Invalid Phone number, Please try again.";
        }

        if (!is_string($cardName) || empty($cardName)) {
            $safe = false;
            $errMsg = "Invalid Name on Card, Please try again.";
        }

        if (!is_string($cardNumber) || empty($cardNumber)) {
            $safe = false;
            $errMsg = "Invalid Card Number, Please try again.";
        }

        if (!is_string($expMonth) || empty($expMonth)) {
            $safe = false;
            $errMsg = "Invalid Exp. Month, Please try again.";
        }

        if (!is_string($expYear) || empty($expYear)) {
            $safe = false;
            $errMsg = "Invalid Exp. Year, Please try again.";
        }

        if (!is_string($cvv) || empty($cvv)) {
            $safe = false;
            $errMsg = "Invalid CVV, Please try again.";
        }

        if (!is_numeric($payment) || empty($payment)) {
            $safe = false;
            $errMsg = "Invalid payment, Please try again.";
        }

        if ($safe) {
            //building user info array
            $values = array(
                'invoiceID'     =>  $_GET['id'],
                'jobID'         =>  $invoice['0']['jobID'],
                'amountPaid'    =>  $payment,
                'datePaid'      =>  'NOW()',
            );
            $payment_info = $database->processRequest("createPayment", $values);
            if($payment_info['status'] == true){
                header('Location: invoices.php');
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
        <h1>Pay Invoice</h1>
        <?php if(!empty($errMsg)){echo $errMsg;} ?>
        <form action="" id="billingForm" method="POST">
            <fieldset id="billingInfo">
                <legend>Billing Info</legend>
                <label for="billingName">Name:</label>
                <input type="text" id="billingName" name="billingName" placeholder="John M. Doe" value="<?php if(isset($_POST['billingName'])){echo $_POST['billingName'];};?>"><br>

                <label for="billingEmail">Email: </label>
                <input type="text" id="billingEmail" name="billingEmail" placeholder="john@example.com" value="<?php if(isset($_POST['billingEmail'])){echo $_POST['billingEmail'];};?>"><br>

                <label for="billingName">Address:</label>
                <input type="text" id="billingName" name="billingAddress" placeholder="542 W. 15th Street" value="<?php if(isset($_POST['billingAddress'])){echo $_POST['billingAddress'];};?>"><br>

                <label for="billingCity">City:</label>
                <input type="text" id="billingCity" name="billingCity" placeholder="New York" value="<?php if(isset($_POST['billingCity'])){echo $_POST['billingCity'];};?>"><br>

                <label for="billingState">State: </label>
                <input type="text" id="billingState" name="billingState" placeholder="NY" value="<?php if(isset($_POST['billingState'])){echo $_POST['billingState'];};?>"><br>

                <label for="billingZip">Zip: </label>
                <input type="text" id="billingZip" name="billingZip" placeholder="10001" value="<?php if(isset($_POST['billingZip'])){echo $_POST['billingZip'];};?>"><br>

                <label for="billingPhone">Phone: </label>
                <input type="text" id="billingPhone" name="billingPhone" placeholder="999-123-4567" value="<?php if(isset($_POST['billingPhone'])){echo $_POST['billingPhone'];};?>"><br>
            </fieldset><br>

            <fieldset id="paymentInfo"><legend>Payment Info</legend>
                <label for="amountDue">Amount Due:</label>
                <input type="text" id="amountDue" name="amountDue" value="<?php if(isset($balance)){echo $balance['balance'];}?>" readonly><br>

                <label for="payment">Amount Paid:</label>
                <input type="text" id="payment" name="payment" placeholder="0.00"><br>

                <label for="cardName">Name on Card:</label>
                <input type="text" id="cardName" name="cardName" placeholder="John More Doe"><br>

                <label for="cardName">Credit card number:</label>
                <input type="text" id="cardName" name="cardNumber" placeholder="1111-2222-3333-4444"><br>

                <label for="expMonth">Exp. Month:</label>
                <input type="text" id="expMonth" name="expMonth" placeholder="01"><br>

                <label for="expyear">Exp. Year</label>
                <input type="text" id="expyear" name="expYear" placeholder="2018"><br>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="352"><br>

                <input type="hidden"  name="action" value="payment">
                    <br>
                <input type="submit" value="Make Payment" class="button" id="payButton">
            </fieldset>
        </form>
    </div>

<?php require_once('footer.php'); ?>

    </body>

</html>
