<?php
    include('session.php');

    if(isset($_POST['uname']) && isset($_POST['psw']) && isset($_POST['login']))
    {
        $user = $_POST['uname'];
        $pass = $_POST['psw'];
        $values = array('username' => $user, 'password' => $pass);

        $user_info = $database-> processRequest("login",$values);


        if(!empty($user_info["userID"])) // 0 = empty
        {
            // Storing session data
            $_SESSION["user_info"] = $user_info;

            // Redirecting to homepage
            header("location: index.php");

        }
        else
        {
            // Return an error message
            $errMsg = 'Incorrect login information. Please try again.';
        }
    }

?>
  <!DOCTYPE html>
    <html lang="en">

<?php require_once('head.php'); ?>



<?php require_once('header.php'); ?>



<title>Limelight Automotive Login Page</title>


<body>

<h2>Login Form</h2>
<div class="linkContainer">
    <?php if(!empty ($errMsg)) {echo $errMsg;}?>
</div>

<style>
    h2
    {
        color: #cccccc;
        
		text-align: center;
		
    }
    .noLogin
    {
        color: #cccccc;
        margin-left: 500px;
        font-size: large;
    }
    .goodLogin
    {
        color: #cccccc;
        margin-left: 500px;
        font-size: large;
    }
     body {font-family: Arial, Helvetica, sans-serif;}
    form
    {
		box-sizing: content-box;  
  		width: 300px;
  		height: 200px;
  		padding: 30px;
        border: 3px solid #f1f1f1;
		margin: auto;
        	
    }

    input[type=text], input[type=password] {
        width: auto;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    button {
        background-color: #ec0b0b;
        color: white;
        padding: 14px 20px;
        margin: 8px 80px;	
        border: none;
        cursor: pointer;
        width: 100px;
    }

    button:hover {
        opacity: 0.8;
    }

    .cancelbtn {
        width: auto;
        padding: 10px 18px;
        background-color: #f44336;
    }

    .imgcontainer {
        text-align: center;
        margin: 24px 0 12px 0;
    }

    img.avatar {
        width: 40%;
        border-radius: 50%;
    }

    .container {
        padding: 16px;
    }

    span.psw {
        float: right;
        padding-top: 16px;
    }

    /* Change styles for span and cancel button on extra small screens */
    @media screen and (max-width: 300px) {
        span.psw {
            display: block;
            float: none;
        }
        .cancelbtn {
            width: 100%;
        }
    }

    .linkContainer {
        text-align: center;
    }

</style>

<form method="POST">

    <div class="container">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="uname" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" required>

        <button type="submit" name="login">Login</button><br>
    </div>
    <div class="linkContainer">
        <a id="createAccLink" href="createAccount.php">New user? Create Account!</a>
    </div>
	 
</form>


</body>
</html>