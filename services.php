<?php include('session.php'); ?>

    <!DOCTYPE html>
    <html lang="en">

<?php require_once('head.php'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <body>

<?php require_once('header.php'); ?>

<div class="main">
    <div class="section" id="servicesPage">
        <h1>Our Services</h1>
        <div class="grid" id="servicesGrid">
            <div class="service" id="service1">
                <h2>Body Work</h2>
                <div class="serviceDescription"><p>The Limelight Automotive group knows the difference between newer and older paint is a heart attack to someone who is passionate about their vehicle. We do full paint finishes and take pride in every coat of paint that we put onto every classic car, as well as other body work services listed below.</p>
                </div><div class=grid><br>
                    <div class="servBox description">
                        <ul text align = "center">
                            <li>Rust and dent repair</li>
                            <li>Paint </li>
                            <li>Bumper replacement</li>
                            <li>Body panels </li>
                            <li>Upgrades to fuel injection </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="column">
                        <img src="media/BodyWorkOne.jpg" alt="bodyworkone" style="width:80%;height:244.5px;">
                    </div>
                    <div class="column">
                        <img src="media/BodyWorkTwo.jpg" alt="bodyworktwo" style="width:80%;height:244.5px">
                    </div>
                    <div class="column">
                        <img src="media/BodyWorkThree.jpg" alt="bodyworkthree" style="width:80%;height:244.5px;">
                    </div>
                </div>

            </div>
        </div>

            <div class="service" id="service2">
                <h2>Restoration</h2>
                <div class="serviceDescription"><p>For over 40 years, our team has consisted of highly-skilled professionals that are passionate about restoring our customers' classic vehicles to keep them in tip-top shape. Our team knows that older vehicles aren’t the easiest to maintain, and that's why we'll make sure your classic car runs and looks better than it ever has before. </p>
                </div><div class=grid><br>
                    <div class="servBox description">
                        <ul text align = "center">
                            <li>Interior</li>
                            <li>Frame off</li>
                            <li>Engine repair </li>
                            <li>Headers</li>
                            <li>Brakes </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="column">
                        <img src="media/RestorationOne.jpg" alt="restoration_one" style="width:80%;height:216.8px;">
                    </div>
                    <div class="column">
                        <img src="media/RestorationTwo.jpg" alt="restoration_three" style="width:80%;height:216.8px;">
                    </div>
                    <div class="column">
                        <img src="media/RestorationThree.jpg" alt="restoration_three" style="width:80%;height:216.8px;">
                    </div>
                </div>

            </div>
            <div class="service" id="service3">
                <h2>Miscellaneous</h2>
                <div class="serviceDescription"><p>In addition to restoration, we also provide car maintenance to keep your car running great for years to come.   We offer a variety of different miscellaneous services that will make your car feel and look brand new.  Our shop is fully staffed with the best professionals who come together and treat customers vehicles with the utmost respect.</p>
                </div><div class=grid><br>
                    <div class="servBox description">
                        <ul text align = "center">
                            <li>Vehicle appraisals</li>
                            <li>Detailing services </li>
                            <li>Fluid check</li>
                            <li>Oil and filter changes</li>
                            <li>Window tinting </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="column">
                        <img src="media/MiscOne.jpg" alt="one" style="width:80%;height:190.08px;">
                    </div>
                    <div class="column">
                        <img src="media/MiscTwo.jpg" alt="two" style="width:80%;height:190.08px;">
                    </div>
                    <div class="column">
                        <img src="media/MiscThree.png" alt="three" style="width:80%;height:190.08px">
                    </div>
                </div>

            </div>
        <h3>Customer Reviews</h3>
        <div class="slideshow-container">

            <div class="mySlides">
                <q>Limelight Restoration restored my car to it's former glory. I would recommend them to anyone restoring needs.</q>
                <p class="customer">- John Apple</p>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
            </div>

            <div class="mySlides">
                <q>These guys are great. They are really good at what they do. Cleanest shop in town. I would recommend them to anyone.</q>
                <p class="customerone">- Lisa Ann</p>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
            </div>

            <div class="mySlides">
                <q>I appreciate all the individuals who put my vehicle together again to make it whole. Outstanding work! Dedication and teamwork!</q>
                <p class="customertwo">- Thomas Reese</p>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
            </div>

            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>




            <script src="customer_reviews.js"></script>
</div>


    </body>

<?php require_once('footer.php'); ?>
</html>
