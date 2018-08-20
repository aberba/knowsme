$(function() {
    
    
   /*----  Navigation Panel Events ---  */
   $(".profile-section").show();
   $(".profile-btn").addClass("active");
   $(".heading span").text("Profile");
   
   $(".menu").on("click", function(e) {
       e.preventDefault();
       $(".menu-item").hide();
       $(".menu").removeClass("active");
   });
   
   $(".profile-btn").on("click", function(e) {
       e.preventDefault();
       $(".profile-section").show();
       $(this).addClass("active");
       $(".heading span").text("Profile");
   });
   
   $(".friends-btn").on("click", function(e) {
       e.preventDefault();
       $(".friends-section").show();
       $(this).addClass("active");
       $(".heading span").text("Friends");
   });
   
   $(".photos-btn").on("click", function(e) {
       e.preventDefault();
       $(".photos-section").show();
       $(this).addClass("active");
       $(".heading span").text("Photos");
   });
   
   $(".widgets-btn").on("click", function(e) {
       e.preventDefault();
       $(".widgets-section").show();
       $(this).addClass("active");
       $(".heading span").text("Widgets");
   });
   
   
   $(".settings-btn").on("click", function(e) {
       e.preventDefault();
       $(".settings-section").show();
       $(this).addClass("active");
       $(".heading span").text("Settings");
   });
   
   
   
   /*----  Profile  Section Events ---  */
   $(".profile-info-form input[type=text]").on("blur", function(e) {
        users.validate(this);
        //alert("blur is working");
   });
   
   $(".profile-info-form").on("submit", function(e) {
        e.preventDefault();
        users.saveProfile();
   });
   
   
   
   
   /*----  Friends Section Events ---  */
   $(".friends-section .friends-search-form").on("submit", function(e) {
       e.preventDefault();
       var $name = $("input[type=text]",this).val();
       Friends.searchFriend($name);
   });
   
   $(".friends-section .show-all-btn").on("click", function(e) {
       e.preventDefault();
       Friends.fetchAll();
   });
   
   $(".unfriend-btn").on("click", function(e) {
       e.preventDefault();
       var user_id = $(this).attr("id");
       users.unFriend(user_id)
   });
   
   
   
   /*----  Photos Section Events ---  */
   $(".photos-btn").click();
   
   $(".album").on("click", function() {
       var $album_id = $(this).attr("id").split("album")[1];

       Album.show($album_id);
       $(".album-lightbox .close").bind("click", function(e) {
            e.preventDefault();
            Album.close(); 
       });
   });
   
   $(".photos-list img").on("click", function() {
       
   });
   
   
   Notification.showMessage("Ooops! error sending your message");
   
});

function newElement(tag) {
    return document.createElement(tag);
}





users = {
    unFriend: function(user_id) {
        var url = "unfriend=yes&user_id="+user_id;
        $.post("ajax/users.php", url, function(e) {
            //alert(e);
            if(e == 1) {
                $("#friend"+user_id).fadeOut("slow");
            }
        });
    }, 
    validate: function(input) {
        var input_id, input_name, input_data, url;
        input_id   = input.id;
        input_name = input.name;
        input_value =  input.value;
        
        //exception for optional inputs
        if(input_name == "oname" && input_value =="") {
            return false;
        }
        
        url  = "validate=yes&input_id="+input_id+"&input_name="+input_name+"&input_value="+input_value;
        
        $.post("ajax/validate.php", url, function(data) {
            //alert(data);
            if(data == 1) {
                $("#"+input_id).removeClass("invalid").addClass("valid");
            }else {
                $("#"+input_id).removeClass("valid").addClass("invalid");
            } 
        });
         
    },
    saveProfile: function() {
        var form = $(".profile-info-form").serialize();
        
        $.post("ajax/users.php", form, function(result) {
           alert(result); 
        });
    }
}




Friends = {
    genListTemplate: function($jsonData) {
         $data = $jsonData;
         
         var $template = $("<div />");
         for(var i in $data) {
             var $uid    = $data[i].user_id;
             
             var $uname  = $data[i].name;
               
             var $mainDiv = $("<div />", {"class":"friend clearfix", "id": $uid});
             var $image   = $("<img />").attr("src", $data[i].profile_photo);
             var $figure  = $("<figure />", {"class":"profile-image"});
             $($figure).append($image);
             
             var $infoDiv = $("<div />");
             var $aname   = $("<a />", {"href": "#", "text": $uname});
             var $pname   = $("<p />", {"class":"name"}).append($aname);
             var $pcountry = $("<p />", {"text": $data[i].country});
             var $msgBtn = $("<button />", {"id": $uid, "class":"button msg-btn", "text": "Message"});
             var $unfriendBtn  = $("<button />", {"id": $uid, "class":"button unfriend-btn", "html": "&#45; Unfriend"});
             
             $($infoDiv).append($pname).append($pcountry).append($msgBtn).append($unfriendBtn);
             $($mainDiv).append($figure).append($infoDiv);
            
            $($template).append($mainDiv); 
         }
         return $template;
    },
    searchFriend: function(name) {
        $name = name.trim();
        if($name.length < 3) return false;
        var $url = "search_friend=yes&name="+$name;
        
        $.post("ajax/search.php", $url, function(e) {
           $(".friends-section .friends-list").html("");
           
           if(e == 0) {
               var $msg = "<p>No results found for <strong>"+$name+"</strong>";
               $(".friends-section .results-section").html($msg);
               return false;
           }
           
           var $data = JSON.parse(e);
           var $num_results = $data.length;
           var $num_msg = "<p><strong>"+$num_results+"</strong> result(s) found for <strong>"+$name+"</strong></p>";
           $(".friends-section .results-section").html($num_msg);
           
           // Generate Template With JSON data
           $template = Friends.genListTemplate($data);
           $(".friends-section .friends-list").append($template);   
        });
    },
    
    fetchAll: function() {
        var $url = "fetch_all_friends=yes";
        
        $.post("ajax/search.php", $url, function(e) {
            $(".friends-section .friends-list").html("");
           
            if(e == 0) {
               var $msg = "<p>You have no friends yet</strong>";
               $(".friends-section .results-section").html($msg);
               return false;
            }
           
           var $data = JSON.parse(e);
           var $num_results = $data.length;
           var $num_msg = "<p>You have <strong>"+$num_results+"</strong> friends</strong></p>";
           $(".friends-section .results-section").html($num_msg);
           
           // Generate Template With JSON data
           $template = Friends.genListTemplate($data);
           $(".friends-section .friends-list").append($template);    
        });
    }
}



Album = {
    show: function($album_id) {
       var $photos_num  = $("<span />", {"class":"photos-num"});
       var $album_title = $("<h3 />", {"class":"title", "text":"Photo Album"}).append($photos_num);
       var $album_notif = $("<div />", {"class":"album-notification"}).html($("<p />"));
       var $album_close = $("<button />", {"class":"close","text":"X"});
       
       
       var $album_header = $("<div />",{"class":"album-header bg-blue clearfix"}).append($album_close).append().append($album_title).append($album_notif);
        
       var $preview_image = $("<img />");
       var $preview_div   = $("<div />", {"class":"preview-div"}).append($preview_image);
       var $photos_div    = $("<div />", {"class":"photos-div"});
       
       
       var $photos_wrap  = $("<div />", {"class":"photos-wrap"}).append($preview_div).append($photos_div);
       var $comments_div = $("<div />", {"class":"comments-div"});
       var $container  = $("<section />", {"class":"container clearfix", "id":$album_id});
       $($container).append($album_header).append($comments_div).append($photos_wrap);
    
       var $add_btn    = $("<button />",{"class":"add-btn button", "text":"+ Add"});
       var $upload_btn = $("<button />",{"class":"upload-btn button", "text":"Upload"});
       
       var $form_status = $("<div />", {"class":"form-status","text":"Upload more photos"});  
       var $input       = $("<input />", {"type":"file", "id":"1", "name":"file[]"});
       var $upload_form = $("<form />", {"class":"upload-form clearfix"}).append($form_status).append($input).append($add_btn).append($upload_btn);
       var $img_wrap = $("<div />", {"class":"images-wrap"});
       var $form_div = $("<div />", {"class":"form-div"}).append($upload_form).append($img_wrap);
       
       var $settings_div = $("<div />", {"class":"settings-div"}); 
       var $upload_panel = $("<div />", {"class":"upload-panel clearfix"}).append($settings_div).append($form_div);
       
       
       $($container).append($upload_panel);
       var $lightbox = $("<div />", {"class":"album-lightbox", "id":$album_id}).append($container);
       $("body").append($lightbox).fadeIn("slow");
        
        
       Album.fetchPhotos($album_id);
       
       // Bind respond Handlers
       $(".container .photos-div img").bind("click", function() {
            //Album.changeSelectedPhoto(this); 
       });
       
       $(".container .add-btn").bind("click", function(e) {
           e.preventDefault();
           var $last_input = $(".container .upload-form input[type=file]").last();
           
           $($last_input).click();//trigger click event to preview photo
           
           $($last_input).bind("change", function() {
                Album.previewPhoto();
           });   
       }); 
                    
       $(".container .upload-btn").bind("click", function(e) {
            e.preventDefault();
            Album.uploadPhotos($album_id);
       });   
         
       $(".album-header .close").bind("click", function(e) {
           e.preventDefault();
           Album.close();
       });
    },
    fetchPhotos: function($album_id) {
        var $url = "fetch_album_photos=yes&album_id="+$album_id;
        
        $.post("ajax/album.php", $url, function(e) {   
           if(e == 0) {
               var $msg = "No images uploaded into album";
               Album.showMessage($msg);
               return false;
           }
        
           var $data = JSON.parse(e);
        
           for(var i in $data) { 
              var $photo_path = $data[i].path;
              var $photo_id   = $data[i].photo_id;
       
              var $photo = $("<img />", {"id":$photo_id, "src":$photo_path});
              $(".container .photos-div").append($photo);
           }
           var $preview = $(".container .preview-div img");
           var $current_photo = $(".container .photos-div img:first-child");
           var $current_photo_id = $current_photo.attr("id");
           
           var $current_photo_src = $($current_photo).attr("src");
           Album.fetchPhotoComments($current_photo_id);
           
           $preview.attr("src", $current_photo_src);
           $($current_photo).addClass("current");
           
           
           //Bind respond handlers 
           $(".container .photos-div img").bind("click", function() {
               var $this = $(this);
               var $id = $this.attr("id");
               $(".container .photos-div img").removeClass("current");
               $this.addClass("current");
              
               
               $preview.attr("src", $this.attr("src"));
               Album.fetchPhotoComments($id);
           });
           
       });
    },
    fetchPhotoComments: function($photo_id) {
         // Fetch album data
        var $url = "fetch_photo_comments=yes&photo_id="+$photo_id;
        $.post("ajax/album.php", $url, function(e) {
           //alert(e);       
           if(e == 0) {
              var $msg = "<p class='note'>No comment is posted on this photo</p>";
              $(".container .comments-div").html($msg);
              return false;
           }
           var $data = JSON.parse(e);
        
           $(".container .comments-div").html("");
           
           for(var i in $data) { //interate through $data
              var $user_id    = $data[i].user_id;
              var $user_name  = $data[i].user_name;
              var $user_photo = $data[i].user_photo;
              var $comment    = $data[i].comment;
              var $date       = $data[i].date;
                   
              var $com_photo = $("<img />", {"src":$user_photo});
              var $time      = $("<span />", {"class":"date", "text":$date});
              var $uname     = $("<span />", {"class":"name", "text":$user_name});
              var $p_info    = $("<p />").append($uname).append(" ").append($time);
              var $p_com     = $("<p />").append($comment);
                   
              var $ucom_div   = $("<div />", {"id":$user_id,"class":"comment clearfix"}).append($com_photo).append($p_info).append($p_com);
              
              $(".container .comments-div").append($ucom_div);
           }
       }); // End of Ajax fetch photos and comments
    },
    
    changeSelectedPhoto: function(e) {
        var url;
        $(".photos-list img").removeClass("current");
        $(e).addClass("current");
        url = $(e).attr("src");
        $(".preview img").attr("src", url);
    },
    
    previewPhoto: function() {
       var $input = $(".container .upload-form input[type=file]").last();
       var $this_id   = $($input).attr("id");
       var $last_id   = parseInt($($input).attr("id")) + 1;
       var $input_val = $($input).val();
       var $ext = $input_val.substring($input_val.lastIndexOf(".") + 1).toLowerCase();
       //alert($num_inputs);
       $inputs_num  = parseInt($(".container .upload-form input[type=file]").length);
    
       // Max allowed uploads    
       if($inputs_num >= IMG_MAX_UPLOADS) {
          var $msg = "Maximum allowed image uploads reached";
          Album.showMessage($msg);
          return false;
       }
       
       // File ext     
       if($ext != "gif" && $ext != "png" && 
       $ext != "jpeg" && $ext != "jpg") {
          var $msg = "Unsupported image file format";
          Album.showMessage($msg);
          return false;
       }
       
       // Form Files 
       $input_file = $input[0]; 
       if(!$input_file.files || !$input_file.files[0]) {
           $msg = "Ooops! error reading image file";
           Album.showMessage($msg);
           return false;
       }
       
       // File size 
       $file_size = $input[0].files[0].size;
       if($file_size > IMG_MAX_SIZE) {
           $msg = "Image file must not exceed 1MB";
           Album.showMessage($msg);
           return false;
       }
       
       //File reader
       var $reader = false;
       var $reader = new FileReader();   
       if(!$reader) {
           var $msg = "Sorry!, your browser does not support file reader";
           Album.showMessage($msg);    
           return false;
       }
            
       $reader.onload = function(e) { 
          var $img     = $("<img />", {"id":$this_id, "src":e.target.result});
          var $img_div = $("<div />", {"id":$this_id, "class":"image-div"}).append($img);
          $(".container .upload-panel .form-div .images-wrap").append($img_div).fadeIn("slow");
       }
       $reader.readAsDataURL($input_file.files[0]);
       
       var $new_input = $("<input />", {"type":"file", "name":"file[]", "id": $last_id}); 
       $($new_input).insertAfter($input);    
    },
    
    uploadPhotos: function($album_id) {
        alert($album_id)
        $formdata = false;
        var $formdata = new FormData();
        
        if(!$formdata)  {
            var $msg = "Sorry!, your browser does not support form data";
            Album.showMessage($msg);
            return false;
        }
        
        //validate image seletion
        if($(".container .upload-form input[type=file]").val() == "") {
            var $msg = "No image file has been selected";
            Album.showMessage($msg);
            return false;
        }

        $inputs_num   = parseInt($(".container .upload-form input[type=file]").length);
        for(var i=0; i<$inputs_num; i++) {
           $file = $(".container .upload-form input[type=file]")[i].files[0];
           $formdata.append("file[]", $file);  
        }
        
        $formdata.append("upload_album_photos", "yes");
        $formdata.append("album_id", $album_id);
        
        $(".container .upload-form").fadeOut("slow"); 
        $(".container .upload-form .form-status").html("Please wait ...");     
        
        $.ajax({
           type: "POST",
           url : "ajax/album.php",
           data: $formdata,
           enctype: "multipart/form-data",
           multiple: "multiple",
           processData: false,
           contentType: false,
           success: function(e) {
              if(e == 0) {
                 $msg = "Ooops! error uploading image files";
                 Album.showMessage($msg);
                 return false;
              }
              alert(e);
              Album.fetchPhotos($album_id);
              
              var $new_input = $("<input />", {"type":"file", "name":"file[]", "id":"1"});

              $(".container .form-div .images-wrap").html(""); 
              $(".container .upload-form input[type=file]").replaceWith(" ");
              $($new_input).insertBefore(".container .upload-form .add-btn");
              
              
              $msg = "Uploaded successfully";
              Album.showMessage($msg);
              $(".container .upload-form").fadeIn("slow");
              $(".container .upload-form .form-status").html("Complete!");      
           },
           error: function(e) {
              $msg = "Ooops! error making connection";
              Album.showMessage($msg);
              $(".container .upload-form").fadeIn("slow");
              $(".container .upload-form .form-status").html("Complete!");   
           }
        });
    },

    showMessage: function($message) {
        $(".container .album-header .album-notification p").html($message);
        $(".container .album-header .album-notification").fadeIn("slow");
        
        setTimeout(function() {
           $(".container .album-header .album-notification p").html("");
           $(".container .album-header .album-notification").fadeOut("slow"); 
        }, 7000);
    },
    
    close: function() {
        $(".album-lightbox").fadeOut("slow").remove();
        $(".album-lightbox").replaceWith(""); 
    }    
}














