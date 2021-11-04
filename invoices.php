<?php

    include('session.php');

    // Checking for users Invoices
    if (!empty($_SESSION["user_info"]["userID"])){

        $values = array(
            'userID' => $_SESSION["user_info"]["userID"],
            'invoiceID' => null,
        );

        $invoices = $database->processRequest("getInvoice", $values);

    }

    //count for alternating rows
    $count = 2;

    //var_dump($invoice);
    //var_dump($invoice["workPerformed"]);

?>

<!DOCTYPE html>
<html lang="en">

    <?php require_once('head.php'); ?>

    <body>

    <?php require_once('header.php'); ?>

    <div class="main">
        <h1>My Invoices</h1>
        <?php
            // Displaying users Invoices with dynamic link to payInvoice.php
            if( !empty($invoices['error'])){
                echo $invoices['error'];
            }
            elseif(!empty($invoices)) {

                foreach ($invoices as $invoice) {
                    $values = array('jobID' => $invoice['jobID']);
                    $balance = $database->processRequest("getBalance", $values);
                    if($balance['balance'] !== 0) {
                        if ($count % 2 == 0) {
                            echo '<div class="invoice-even">' . '<div>' . $invoice["carMake"] . " - " . $invoice["carModel"] . " - " . $invoice["carYear"] . '</div>' . '<div>' . "Invoice ID: LAR00" . $invoice["invoiceID"] . '</div>' .
                                '<div>' . '<a href="payInvoice.php?id=' . $invoice['invoiceID'] . '">Pay Invoice</a>' . '</div></div><br>';
                            $count++;
                        } else {
                            echo '<div class="invoice-odd">' . '<div>' . $invoice["carMake"] . " - " . $invoice["carModel"] . " - " . $invoice["carYear"] . '</div>' . '<div>' . "Invoice ID: LAR00" . $invoice["invoiceID"] . '</div>' .
                                '<div>' . '<a href="payInvoice.php?id=' . $invoice['invoiceID'] . '">Pay Invoice</a>' . '</div></div><br>';
                            $count++;
                        }
                    }
                }
            }
            else {
                echo "No Invoices Found!";
            }
        ?>
    </div>

    <?php require_once('footer.php'); ?>

    </body>
</html>
