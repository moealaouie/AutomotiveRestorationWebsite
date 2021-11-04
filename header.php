<?php

/*
 *
 * header.php
 * CIS-294 Project header
 * version     2019.10.03
 *
*/


?>
 <header class="pageHeader">

        <div class="banner">

            <div class="logo">
                <img  id="bannerLogo"  src="media/logoWords.png">
            </div>
        </div>
     <br>
     <br>
     <br>
     <div class="searchNav">
         <?php
            //welcome message if user is  logged in
             if(!empty($_SESSION['user_info']['name']))
             {
                 echo '<div class="welcome">Welcome ' . $_SESSION['user_info']['name'] . '</div>';
             }
         ?>
        <navigation class="navLinks">
            <a href="index.php">Home</a>
            <a href="aboutUs.php">About Us</a>
            <a href="services.php">Services</a>
            <a href="contactUs.php">Contact Us</a>
            <?php
                if (empty($_SESSION["user_info"])):
            ?>
            <a href="TestLogin.php">Log in</a>
            <?php
                else :
            ?>
                    <div class="dropdown">
                        <button class="dropbtn">My Account
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="invoices.php">Invoices</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
            <?php
                endif;
            ?>
        </navigation>
    </div>
 </header>
<br>




