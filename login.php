<?php

require_once("includes/initialize.php");
$Session->login (1, "yes");

if($Session->logged_in()) {
    redirect_to("notification.php");
}

$page_title = "Login &raquo; ".SITE_NAME;
$css        = "login.css";
$javascript = "login.js";

// Form procesing when submitted
if(isset($_POST['login'])) {
    $errors = array();
    if(empty($_POST['email'])) {
        $errors['email'] = "Please enter your Email address";
    }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid Email address";
    }else {
        $email = $Database->clean_data($_POST['email']);
    }
    
    if(empty($_POST['password'])) {
        $errors['password'] = "Please enter your password";
    }else {
        $password = $_POST['password'];
    }
    
    $set_cookie = (!empty($_POST['set_cookie'])) ? "yes" : "no";   
    
    if(empty($errors)) {
        
      // check for record of user in the db, this returns user ID if true
      $record = $User->authenticate($email, $password);  
      
      if($record && $Session->login($record, $set_cookie)) {
         redirect_to("notification.php");
      }else {
        $message = "Invalid Email address and password";
      }  
    }
}


$style = 'login.css';

?>
<html>
<head lang="en">
    <title><?php echo @$page_title; ?></title>
    <!-- META  -->
    <meta charset="utf-8" />
    
    <!-- CSS  -->
  	<link rel="stylesheet" type="text/css" href="./css/general.css">
  	<link rel="stylesheet" type="text/css" href="./css/<?php echo $css; ?>">
    
    <!-- JAVASCRIPT  -->
    <script type="text/javascript" src="./js/general.js"></script>
  	<script type="text/javascript" src="./js/<?php echo $js; ?>"></script>
</head>
 <body>
 
  <article id="alt-wrapper">
  
    <section class="page-view clearfix">
       <div class="form-wrapper">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form" method="post">
            <h3>Log into your account</h3><br />
          
            <p>
               <input type="email" name="email" id="email" size="25" maxlength="40" 
                placeholder="Email Address ..." 
                value="<?php echo @htmlentities($_POST['email']); ?>"/>      <br /> 
               <span><?php echo @show_error($errors['email']); ?></span>
            </p><br />
        
            <p>
              <input type="password" name="password" id="password" size="25" 
              maxlength="40" placeholder="Password ..."/>
               <span class="error"><?php echo @show_error($errors['password']); ?></span>
            </p>
            <p><?php echo @show_error($message); ?></p>
            
               <div class="login-options">
                  <p><a href="#">Forgot your password?</a></p>
                  <p><a href="index.php">Sign Up</a></p>
               </div>
            
            <p>
               <input type="checkbox" name="set_cookie" value="false" 
               <?php echo (isset($_POST['set_cookie'])) ? "checked" : null; ?> />
               <label for="set_cookie">Keep me logged in</label>
            </p>
            <input type="submit" name="login" id="submit" value="Login" />
          </form>
        </div>
    </section>
    
  </article> <!-- END of wrapper -->
 </body>
</html> 
