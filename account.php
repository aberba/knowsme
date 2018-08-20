<?php
require_once("includes/initialize.php");

if(!$Session->logged_in()) {
    redirect_to("login.php");
}

$session_id = $Session->user_id();
$user_name = "Aberba Lawrence";

$upload_path = $Uploads->uploads_path();


$css = 'account.css';
$js  = "account.js";
$page_title = "Home &raquo; ".SITE_NAME;


//$res = $Uploads->make_gallery_dir();

//$hey = $Photos->create_album_dir("mancaptain");


include_template("header.php");

 ?>
 <!-- aside for page with user information-->

 <!-- user profile aside box 1: profile information-->
<aside id="aside">
   
   <section id="aside-box" class=" clearfix">
      <h3>Make more friends</h3>
      <div>
         <figure class="profile-image">
            <img src="img/image.png" width="100" />
         </figure>
      
         <div>
            <h4><a href="#">Aberba lawrence</a></h4>
            <p>Ghana</p><br />
         
            <p>Friends: 500</p>
            <p>Since 1st March, 2014</p>
         </div>
      </div> 
   </section>
   
   <section id="aside-box" class="aside-menu clearfix">
      <ul>
         <li class="menu button profile-btn"><span>
         <img src="img/icons/user-20.png" /></span> Profile 
         <span class="arrow">&raquo;</span></li>
         
         <li class="menu button friends-btn"><span>
         <img src="img/icons/friends-20.png" /></span> Friends 
         <span class="arrow">&raquo;</span></li>
         
         <li class="menu button photos-btn"><span>
         <img src="img/icons/photos-20.png" /></span> Photos 
         <span class="arrow">&raquo;</span></li>
         
         <li class="menu button widgets-btn"><span>
         <img src="img/icons/photo-stack-10.png" /></span> Widgets 
         <span class="arrow">&raquo;</span></li>
         
         <li class="menu button settings-btn"><span>
         <img src="img/icons/settings-20.png" /></span> Settings 
         <span class="arrow">&raquo;</span></li>
         
      </ul>
   </section>
   	
</aside>



 
<div id="content">
   <div id="content-box">
      <div class="content-section">
         <h4 class="heading">Account Settings &raquo; <span></span></h4>
      </div>
        
      <section class="content-section menu-item profile-section"><!--Profile -->
      <?php
         $Profile = $User->fetch_user();
         if($Profile) {
      ?>
           <div>
              <h3>Change your profile information</h3>
              
              <form id="profile-info-form" class="form profile-info-form" method="get">
                 <fieldset><legend>Basic Information</legend>
                    <input type="text" name="fname" id="fname" 
                    placeholder="First Name" 
                    value="<?php echo $Profile->first_name; ?>" />
   
                    <input type="text" name="lname" id="lname" 
                    placeholder="Last Name" 
                    value="<?php echo $Profile->last_name; ?>" />
                
                    <input type="text" name="oname" id="oname" 
                    placeholder="Other Name" 
                    value="<?php echo $Profile->other_name; ?>" />
                    
                    
                    <div class="select-div">
                      <select name="birth-month">
                        <option>January</option>
                        <option>February</option>
                      </select>
                      
                      <select name="birth-day">
                        <option>01</option>
                        <option>02</option>
                      </select>
                      
                      <select name="birth-year">
                        <option>2014</option>
                        <option>2015</option>
                      </select>
                    </div>
                    
                    
                    <input type="text" name="hometown" id="hometown" 
                    placeholder="Your Hometown" 
                    value="<?php echo $Profile->hometown; ?>" />
                    
                    <input type="text" name="current-city" id="current-city" 
                    placeholder="Your Current City" 
                    value="<?php echo $Profile->current_city; ?>" />
                 
                 </fieldset>
                 
                 <fieldset><legend>Education and Work</legend>
                    <input type="text" name="high-school" id="high-school" 
                    placeholder="Your High School" 
                    value="<?php echo $Profile->high_school; ?>" />
                    
                    <input type="text" name="college" id="college" 
                    placeholder="College Education" 
                    value="<?php echo $Profile->college; ?>" />
                    
                    <input type="text" name="university" id="university" 
                    placeholder="University Education" 
                    value="<?php echo $Profile->university; ?>" />
                    
                    <input type="text" name="profession" id="profession" 
                    placeholder="Your Current Profession(s)" 
                    value="<?php echo $Profile->profession; ?>"  />
                 </fieldset>
                 
                 <fieldset><legend>About you</legend>
                     <textarea name="biography" placeholder="Write Something about your self"><?php echo $Profile->biography; ?></textarea>
                 </fieldset>
                 
                 <input type="hidden" name="save-profile" value="true" />
                 
                  <button id="save-profile-btn" class="button">Save</button>
              </form>
           </div>
           
        <?php } ?>
      </section> <!--end of profile -->
      
      
      
      <section class="content-section menu-item friends-section"> <!--Friends -->
         <div class="friend-search-form-section">
              <form class="friends-search-form">
                 <input type="text" name="search-input" id="search-input" 
                 placeholder="Search for friend ..." />
                 
                 <button type="button" class="show-all-btn button">Show all</button>
              </form>
              
         </div>
      <?php    
      $friends = $User->fetch_friends();
      $num_friends     = count($friends);
      
      echo "<div class='results-section'>
                <p>You have <strong>{$num_friends}</strong> friends</p>
           </div>
           
          <section class='friends-list'>";
      
      if($friends) {
        foreach ($friends as $friend => $value) { ?>
          <div class="friend clearfix" id="friend<?php echo $value['user_id']; ?>">
             <figure class="profile-image">
                <img src="<?php echo $value['profile_photo']; ?>" />
             </figure>
          
             <div>
                <p class="name"><a href="#"><?php echo $value['name']; ?></a></p>
                <p><?php echo $value['country']; ?></p>
                <button id="<?php echo $value['user_id']; ?>" class="button msg-btn" >Message</button>
                <button id="<?php echo $value['user_id']; ?>" class="button unfriend-btn">&#45; Unfriend</button>
             </div>
          </div>     
      <?php } 
      
      }else {
         echo "<p class='result'>No have no friends yet</p>";
      }
      ?>
         </section>
      </section> <!--End of Friends -->
      
      
      <section class="content-section menu-item photos-section"> <!--Photos -->
           <h1>Photos</h1>
           
           <section class="photos-status-section">
              <h4>Your Photos Current Status</h4>
              <p>300 Photos</p>
              <p>6 Albums</p>
           </section>
           
           <section class="action-buttons">
              <button class="button">New Album</button>
           </section>
      
           <section class="albums-section clearfix">
        <?php
           $albums = $Photos->fetch_albums($session_id);
           
           if($albums) {
              foreach($albums as $album => $value) {
                  echo '<div id="album'.$value['album_id'].'" class="album">
                           <h3 class="bg-blue">'.$value['album_name'].' <span class="close">X</span></h3> 
                           <img src="'.$value['album_cover'].'" width="50" />
                        </div>';
              } 
           }
        ?>
          </section>
            
      </section> <!--End of Photos -->
      
      
      <section class="content-section menu-item widgets-section"> <!--Widgets -->
           <h1>Widgets</h1>
           
      </section> <!--End of widgets -->
      
      
      
      <section class="content-section menu-item settings-section"> <!--Security -->
           <h1>Settings</h1>
           
      </section> <!--End of security -->
      
      
      
      
      <!-- Footer -->
      <section class="content-section post-form-wrapper">
         <p>&copy; knowsMe <?php echo date("Y", time()); ?></p>
      </section>
      	
   </div>
   
</div>

<?php include_template("footer.php"); ?>