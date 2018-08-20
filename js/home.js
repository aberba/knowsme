$(function() {
    var session_id = Session.fetchSession();
    
    $("#add-photo-btn").on("click", function(e) {
         e.preventDefault();
         post.selectPhoto();
    });
    
    $("#post-photo").change(function() {
         $("#post-photo-status").html($(this).val()).show();
         $(".photo-section").show();
         $(".photo-section input[type=file]").hide();
    });
    
    $("#add-video-btn").on("click", function(e) {
         e.preventDefault();
         $(".photo-section").hide();
         $(".video-section").show();
    });
    
    
     
    
    $("#post-btn").on("click", function(e) {
        e.preventDefault();
        var postInput, inputText, inputStatus;
        
        postInput = $("#post-input");
        inputText = postInput.val();
        inputStatus = $("#post-input-status");
        
        postInput.on("focus", function(e) {
            inputStatus.fadeOut("slow");
        });
        
        if(inputText.length < 1) {
            inputStatus.html("Please type something to post").fadeIn("slow");
            return false;
        }
        
        alert(inputText); 
    });
    
    
    
    //Plus, Minus && toggle on post actions
    $(".plus").on("click", function() {
        var post_id;
        post_id = $(this).attr("id").split("plus")[1];
        post.addPlusOrMinus(post_id, "plus");
    });
    
    $(".minus").on("click", function() {
        var post_id;
        post_id = $(this).attr("id").split("minus")[1];
        post.addPlusOrMinus(post_id, "minus");
    });//End of plus or minus
    
    
    $(".toggle").on("click", function() {
        var post_id;
        post_id = $(this).attr("id").split("toggle")[1];
        $("#comments-section"+post_id).slideToggle("slow");
    });//End of plus or minus
    
    
    
    
    
    // Post comment action
    $(".comment-form input[type=text]").on("focus", function() {
        //post_id = $(this).attr("id").split("comment-input")[1];
        //$("#comments-section"+post_id).show("slow");
    });
    
    $(".comment-form").on("submit", function(e) {
        e.preventDefault();
        var form_id, data, post_id, comment;
        
        form_id = $(this).attr("id").split("form");
        data    = $(this).serialize();
        post_id = form_id[1];
        var session_name  = $("form#session_info #user_name").val();
        
        $.post("ajax/post.php", data, function(e) { 
            if(e != "") { 
               var html = $("<div id='comment"+post_id+"' class='comment'><figure><img src='profile/lawrence.jpg' /></figure><p><a href='#'>"+session_name+"</a></p><p>"+e+"</p></div>");
               
               $("#comments-section"+post_id).append(html);
               $("#comment-input"+post_id).val("");    
            }
        });
    });//End of comment submit
     
   
});






/**************************************************************************************
*       functions 
*
***********************************************************************************/


//Ajax call functions
ajaxCall = {
    make: function(atype, aurl, adata,  abefore, asuccess, aerror) {
        $.ajax({
            type: atype,
            url: aurl,
            data: adata,
            beforeSend: abefore,
            success: asuccess,
            error: aerror 
        });
    }
}


//Loader functions
loader = {
    commentShow: function(post_id) {
        $("#loading"+post_id).fadeIn("slow");
    },
    commentHide: function(post_id) {
        $("#loading"+post_id).fadeOut("slow");
    }
}


//Posts functions
//var post  = new Object();
post = {
   selectPhoto: function() {
       $("#post-photo").click();
   },
   comment: function(e) {
      alert("comment is working");
   },
   update: function(e) {
      alert("update");
   },
   addPlusOrMinus: function(post_id, choice) {
      var url = "choice="+choice+"&post_id="+post_id;
       
      $.post("ajax/post.php", url, function(e) {
          if(e == 1) {
               var btn, btn_content, num, sign;
               sign = (choice == "plus") ? "+ " : "- ";
               
               btn = $("#"+choice+post_id);
               btn_content = btn.text().split(sign);
               num = btn_content[1];
               num++;
               btn.text(sign + num);
               
               $(".plus"+post_id).unbind("click");
               $(".minus"+post_id).unbind("click");
          }
      });
   }
}


//Notifications functions
notification = {
    show: function(title, content) {
        var id = content.length;
        
        notifTemplate  = $("<div id='notification"+id+"' class='notification'><div class='notification-header clearfix'><button id='close"+id+"' class='close'>X</button><h3>"+title+"</div><div class='content'>"+content+"</div><script>$('#close"+id+"').on('click', function() { notification.remove("+id+"); });</script></div>");
        
        setTimeout(function() {
            $("#wrapper").append(notifTemplate).fadeIn("slow");
            //$(".notification").append(content);
           },1000);
    },
    remove: function(id) {
        $("#notification"+id).fadeOut("slow").remove();
    }
}