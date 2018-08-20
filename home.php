<?php
require_once("includes/initialize.php");

if(!$Session->logged_in()) {
    redirect_to("login.php");
}

$session_id  = $Session->user_id();
$Info     = $User->fetch_user();

$session_name = $Info->first_name." ".$Info->other_name." ".$Info->last_name;


$css = 'home.css';
$js  = "home.js";
$page_title = "Home &raquo; ".SITE_NAME;

include_template("header.php");

?>
<aside id="aside">
   <section id="aside-box" class="clearfix profile-info">
      <figure class="profile-image">
         <img src="img/image.png" />
      </figure>
      
      <div>
         <h4><a href="#">Aberba lawrence</a></h4>
         <p>Ghana</p><br />
         
         <p>Friends: 500</p>
         <p>Since 1st March, 2014</p>
      </div>
      <form id="session_info">
         <input type="hidden" id="user_id" value="<?php echo @$session_id; ?>" />
         <input type="hidden" id="user_name" value="<?php echo @$session_name; ?>" />
      </form>
   </section>
   
   <section id="aside-box" class="people-you-know clearfix">
      <h3>Make more friends</h3>
      
      <div class="person" class="clearfix">
         <img src="img/icons/x.png" alt="x" class="close" />
         <figure class="profile-image">
            <img src="img/image.png" />
         </figure>
         <div class="person-info">
           <p class="name">John Lutter</p>
           <p class="subinfo">Canada</p>
           <button class="button">&#43; Befriend</button>
         </div>
      </div><br />
      
      <div class="person" class="clearfix">
         <img src="img/icons/x.png" alt="x" class="close" />
         <figure class="profile-image">
            <img src="img/image.png" />
         </figure>
         <div class="person-info">
           <p class="name">John Lutter</p>
           <p class="subinfo">La Cote D'vore</p>
         
           <button class="button"><img src="img/icons/add-user-w-10.png" /> Befriend</button>
         </div>
      </div><br />
      
      <div class="person" class="clearfix">
         <img src="img/icons/x.png" alt="x" class="close" />
         <figure class="profile-image">
            <img src="img/image.png" />
         </figure>
         <div class="person-info">
           <p class="name">John Lutter</p>
           <p class="subinfo">Canada</p>
           <button class="button">&#43; Befriend</button>
         </div>
      </div>
   </section>
 	
</aside>
 <div id="content">

 
    <div id="content-box">

 	    <div class="content-section"><h4>Posts</h4></div>
        
        <div class="content-section post-form-wrapper">
    
           <form id="post-form" class="post-form">
              <div>
                 <p class="arrow"></p>
                 <textarea id="post-input" maxlength="380" placeholder="Write someting to share " class="post-input"></textarea>
                 <p id="post-input-status" class="post-input-status error"></p>
              </div> 
                 
              <div class="attachments">
                <section id="post-items">
                
                   <div class="photo-section"><input type="file" id="post-photo" />
                   <p id="post-photo-status"></p></div>
                
                   <div class="video-section"><input type="text" id="post-video"
                    placeholder="Video URL (Youtube, Vimeo)" />
                    <button type="button" id="post-photo-btn" class="button">&#43;
                    </button></div> 
                 
                   <div class="link-section"><input type="text" id="post-link" /><button type="button" id="post-link-btn" class="button">&#43;</button></div> 
                </section><br />
                
                
                <button id="add-photo-btn">
                <img src="img/icons/camera-20.png" alt="Add a photo" /></button>
                
                <button id="add-video-btn">
                <img src="img/icons/video-20.png" alt="Add a video" /></button>
                
                <button id="add-link-btn">
                <img src="img/icons/web-link-20.png" alt="Add a link" /></button>
                
              </div>
              
              <input type="hidden" name="user_name" id="user_name" />
              <input type="hidden" name="user_id" id="user_id" 
              value="<?php echo $_SESSION['kme_u_id'];  ?>" />
              
              <button type="button" id="post-btn" class="post-btn button">Share</button>
           </form>
        </div>
        
        <?php 
            $Post =  $Posts->fetch_posts(17);
            if(!empty($Post)) {
                //print_r($Post);
               $output = "";
               foreach ($Post as $p => $value) { 
                   $post_id = $value['post_id'];
        ?>
                  <!-- Page posts section -->
                  <div id="post<?php echo $value['post_id']; ?>" class="content-section post clearfix">
                  
                  <!-- Post user info -->
                     <div class="profile-thumb">
                        <figure class="profile-image">
                            <img src="profile/lawrence.jpg" />
                        </figure>
                     </div>
                    
                    <!-- Post content --> 
                  <div class="post-content-wrapper clearfix"><!-- content wrapper -->
                     <div>
                        <p><span class="name"><a href="#"><?php echo $value['name']; ?></a></span> &nbsp;&nbsp;<span class="date"><?php echo $value['date']; ?></span></p>
                        <p class="post-content"><?php echo $value['post']; ?></p>
                     </div>
           
                     <!-- Post like an comment button section -->
                     <div id="post-status" class="post-status-section">
                        <button id="plus<?php echo $value['post_id']; ?>" 
                        class="plus">+ <?php echo $value['plus']; ?></button>
                        <button id="minus<?php echo $value['post_id']; ?>" 
                        class="minus">- <?php echo $value['minus']; ?></button>
                        
                        <button id="toggle<?php echo $value['post_id']; ?>" 
                        class="toggle"><img src="img/icons/toggle-10.png" /></button>
                     </div>
                     
                     
                     
                     <!-- Comments section for post -->
                     <div id="comments-section<?php echo $value['post_id']; ?>" class="comments-section" style="display: none;">   
                  <?php $comments = $Posts->fetch_comments($value['post_id']); 
                  
                    if(!empty($comments)) { ?>
                       
                       <?php foreach ($comments as $comment => $comm) {?> 
                          <div id="comment<?php echo $value['post_id']; ?>" class="comment">
                             <figure>
                                <img src="profile/lawrence.jpg" />
                             </figure>
                          
                             <p><span class="name"><a href="#"><?php echo $comm['name']; ?></a></span> &nbsp;&nbsp;<span class="date"><?php echo $comm['date']; ?></span></p>
                             <p><?php echo $comm['comment']; ?></p>
                          </div>
                          
                       <?php } ?>
                   <?php } ?>
                   
                   </div><!-- End of comments -->
                     <!-- Comment form for post -->
                     <div id="comment-form-section<?php echo $post_id; ?>" class="comment-form-section">
                        <figure class="profile-thumb">
                          <img src="profile/lawrence.jpg" />
                        </figure>
              
                        <form id="form<?php echo $value['post_id']; ?>" class="comment-form">
                          <input type="text" name="comment" id="comment-input<?php echo $value['post_id']; ?>" 
                           placeholder="Write your comment" maxlength="120" />
                           
                           <input type="hidden" name="post_id" value="<?php echo $value['post_id']; ?>" />
                           <input type="hidden" name="user_id" value="<?php echo $session_id; ?>"  />
                          <input type="hidden" name="user_name" value="<?php echo $session_id; ?>"  /> 
                           <span><img id="loading<?php echo $value['post_id'] ?>" class="loading" src="img/icons/loading.gif" alt=" ..." /></span>
                        </form>
                     </div><!-- End of comment form -->
                     
                </div><!-- End of post content wrapper --> 
           </div> <!-- End of post -->
                <?php } ?> 
           <?php } ?>
        
        <!-- Page footer section -->
        <div class="content-section post">
           <p>&copy; knowsMe <?php echo date("Y", time()); ?></p>
        </div>
        
    </div>
 <div id="right_aside">
 	
 </div>
 	
</div>
 


<?php include_template('footer.php'); ?>