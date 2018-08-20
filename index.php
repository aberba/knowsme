<?php
require_once("includes/initialize.php");

if($Session->logged_in()) {
    redirect_to("notification.php");
}

//echo $Secure->secure_password("aberba1313");

$css = 'index.css';
$js  = "validate.js";
$page_title = "Welcome to ".SITE_NAME;

if(isset($_POST['signup'])) {
    $errors = array();
    $clean  = array();
    
    if(empty($_POST['fname'])) {
        $errors['fname'] = "Please enter your first name";
    }elseif(!isset($_POST['fname'][2])) {
        $errors['fname'] = "First name must be 3 letters or more";
    }else {
        $clean['fname'] = $Database->clean_data($_POST['fname']);
    }
    
    if(empty($_POST['lname'])) {
        $errors['lname'] = "Please enter your last name";
    }elseif(!isset($_POST['lname'][2])) {
        $errors['lname'] = "Last name must be 3 letters or more";
    }else {
        $clean['lname'] = $Database->clean_data($_POST['lname']);
    }
    
    if(empty($_POST['email'])) {
        $errors['email'] = "Please enter your email address";
    }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address";
    }else {
        $clean['email'] = $Database->clean_data($_POST['email']);
    }
    
    if(empty($_POST['gender'])) {
        $errors['gender'] = "Please choose your gender";
    }else {
        $clean['gender'] = $Database->clean_data($_POST['gender']);
    }
    
    if(empty($errors)) {
        if(empty($_POST['password1'])) {
            $errors['password'] = "Please enter your password";
        }elseif(!isset($_POST['password1']['4'])) {
            $errors['password'] = "Your password was too short";
        }elseif(empty($_POST['password2'])) {
            $errors['password'] = "Please confirm your password";
        }elseif($_POST['password1'] != $_POST['password2']) {
            $errors['password'] = "The two passwords donnot match";
        }else {
            $clean['password'] = $Database->clean_data($_POST['password1']);
        }
    }
    
    if(empty($errors)) {
        if($User->add_user($clean)) {
               redirect_to("home.php"); 
        }
    }   
}

?>
<html>
<head lang="en">
    <title><?php echo @$page_title; ?></title>
    <!-- META  -->
    <meta charset="utf-8" />
    
    <!-- CSS  -->
  	<link rel="stylesheet" type="text/css" href="css/general.css">
  	<link rel="stylesheet" type="text/css" href="css/<?php echo $css; ?>">
    
    <!-- JAVASCRIPT  -->
    <script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
    <script type="text/javascript" src="js/general.js"></script>
  	<script type="text/javascript" src="js/<?php echo $js; ?>"></script>
</head>
 <body>
 
  <article id="alt-wrapper">
     <section class="page-view">
        <div class="form-wrapper">
             <form action="login.php" 
              method="post" class="form" id="login-form">
               <h3>Login</h3>
          
               <p>
                 <input type="email" name="email" placeholder="Email Address" />  
               </p>
        
               <p>
                 <input type="password" name="password" placeholder="Password ..."/> 
               </p>
               <p>
                 <input type="checkbox" name="set_cookie" value="false" />
                 <label for="set_cookie">Keep me logged in</label>
               </p>
               <input type="submit" name="login" id="submit" value="Login" />
          </form>
      
    	 <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" 
         class="form" id="signup-form">
    		<h3>Create an account</h3>
            
    		<p><input type="text" name="fname" value="<?php echo @$_POST['fname']; ?>"               placeholder="First Name" id="fname" />
               <?php echo @show_error($errors['fname']); ?>
            </p>
            
    		<p><input type="text" name="lname" value="<?php echo @$_POST['lname']; ?>"                placeholder="Last Name" id="lname" />
               <?php echo @show_error($errors['lname']); ?>
            </p>
            
            <p><input type="text" name="email" 
            value="<?php echo @$_POST['email']; ?>" placeholder="Email Address"
             id="email" />
             <?php echo @show_error($errors['email']); ?>
            </p>
               
            <p><input type="radio" name="gender" value="M" 
            <?php echo (@$_POST['gender'] == "M") ? "checked" : null; ?> />Male 
            &nbsp; &nbsp; &nbsp;
               <input type="radio" name="gender" value="F" 
               <?php echo (@$_POST['gender'] == "F") ? "checked" : null; ?> />Female 
               <br />
               <?php echo @show_error($errors['gender']); ?>
            </p>
    				
            <p><input type="password" name="password1" placeholder="Password" 
            id="password" /></p>
    		<p><input type="password" name="password2" placeholder="Confirm Password"
            id="password" />
            </p>
            
            <!-- Passwords Error -->
    		<?php echo @show_error($errors['password']); ?>
            	  
            <p><input type="submit" name="signup" value="Sign Up!" /></p>
            
            <div class="s"></div>
    	 </form>
        </div>  
     </section>  
   </article>
</body>
</html>