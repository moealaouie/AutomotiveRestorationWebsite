<?php
include('session.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CIS-294 Project Website</title>
    <link rel="stylesheet" href="css/stylesheet.css">

</head>
<body>

<html lang="en">
<body>

<?php require_once('header.php'); ?>

<?php

echo 'Welcome: ' . $_SESSION["firstname"];

?>

<div class="main">
    <div class="section" id="s1">
        <h1>CIS-294 Project Website</h1>
        <p>This is the website for the Fall 2019 CIS-294-70 project.</p>
    </div>
    <div class="section" id="s2">
        <h1>Sample Stuff...</h1>
        <p>an ad, slideshow, call to action, customer testimonial, etc</p>
    </div>

</div>
<div class="section" id="s3">
    <div class="grid">
        <div class="box" id="b1">1</div>
        <div class="box" id="b2">2</div>
        <div class="box" id="b3">3</div>
        <div class="box" id="b4">4</div>
    </div>

    <footer id="pageFooter"> Page footer
    </footer>
</div>


</body>
</html>