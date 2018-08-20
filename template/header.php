<?php
global $page_title, 
       $css, 
       $js,
       $user_id,
       $user_name;
 
?>
<html>
<head lang="en">
    <title><?php echo @$page_title; ?></title>
    <!-- META  -->
    <meta charset="utf-8" />
    
    <!-- CSS  -->
  	<link rel="stylesheet" type="text/css" href="./css/general.css">
  	<link rel="stylesheet" type="text/css" href="./css/<?php echo $css; ?>">
    
</head>
 <body>

<header id="header" class="bg-blue">
   <div id="header_wrapper">
   
      <div id="logo">
         <a href="index.php"><img src="img/template/knowsme_logo.png" /></a>';
      </div>
   
      <!---  Header Navigation div  -->
      <nav id="navigation">
         <ul>
            <li><a href="home.php"><img src="img/icons/home-w-16.png" 
            alt="Home" title="Home" /></a></li>
            
            <li><a href="account.php"><img src="img/icons/account-w-16.png" 
            alt="My Account" title="My Account" /></a></li>
            
            <li><a href="notification.php"><img src="img/icons/notification-w-16.png" 
            alt="Notification" title="Notification" /></a></li>
            
            <li><a href="explore.php"><img src="img/icons/explore-w-16.png" 
            alt="Home" title="Explore" /></a></li> 
         </ul>   
      </nav>
    
    
      <!---  Header Search form div  -->
      <div id="search_div">
    	 <form method="post" action="search.php" id="search">
    		<input type="text" name="q" placeholder="Search ..." 
            value="<?php echo @$_GET['q']; ?>" />
       	 </form>
      </div>
      
      
      <!---  Options Dropdown menu  -->
      <div id="options">
          <img class="show" src="img/icons/show-w-12.png" alt="Options" title="Options" />
          
          <div id="dropdown-menu">
             <ul>
                <li><a href="#">About</a></li>
                <li><a href="#">Privacy</a></li>
                <li><a href="#">Report Abuse</a></li>
                <li><a href="#">Help</a></li>
                <li><a href="logout.php">Logout</a></li>
             </ul>
          </div>
      </div>
      
      
      <!---  Notification Section  -->
      <section id="notification-section">
            <div id="notification"><p></p></div>
      </section>
      
      
      
      <!---  Loader Section  -->
      <section id="loader-section">
           <div id="loader">
              <img src="img/icons/loading.gif" />
           </div>
      </section>
      
   </div>
</header>

<div id="wrapper" class="clearfix">


<!------ Chat Section -------->
<section id="chat-section" class="clearfix">

        <div id="chat-button" class="bg-blue clearfix">
            <img src="img/icons/chat-w-20.png" />
            <p>Chat <span>6 Friends</span></p> 
        </div>
        
   <section id="chat-wrapper" class="clearfix">     
        <div id="chat-header" class="bg-blue clearfix">
             <ul>
                <li><img src="img/icons/settings-w-10.png" /></li>
                <li><img id="close-chat" src="img/icons/x-w-10.png" /></li>
             </ul>
        </div>
        
        <div id="online-friends">
           <ul>
           </ul>
        </div>
        
        <div id="chatbox-wrapper">
            <div class="chatbox">
            
              <div class="message-box"></div>
              
              <form><input type="text" maxlength="100" /></form>
              <ul>
                 <li>@</li>
                 <li>@</li>
                 <li id="clear-messages">&raquo;</li>
              </ul>
            </div>
        </div>
   </section><!--- end of chat  -->
   
</section>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
  	
