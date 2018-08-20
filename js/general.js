$(function() {
   var session_id = Session.fetchSession();
   var session_name = "Aberba Lawrence";
   
   $(window).ajaxStart(function() { 
       $("#header #loader-section #loader").fadeIn();
   });
   
   $(window).ajaxStop(function() { 
       $("#header #loader-section #loader").hide("slow");
   });
   
   $("#wrapper").on("click", function() {
      $("#dropdown-menu").fadeOut("slow");
   });
   
   $("#header #options img.show").on("click", function(e) {
      e.preventDefault();
      $("#header #options ul li").removeClass("bg-blue");
      $("#dropdown-menu").toggle("slow");
   });
   
   $("#header #options ul li").hover(function() {
      $("#header #options ul li").removeClass("bg-blue");
      $(this).addClass("bg-blue");
   });
   
   
   /*----------- CHAT SECTION -----------*/
   $("#chat-section #chat-button").on("click", function() {
      $(this).hide();
      Chat.showChat();
      Chat.fetchOnlineUsers();
   });
   
   $("#chat-section #close-chat").on("click", function() {
      Chat.hideChat();
   });
   
   
   $("#chatbox-wrapper form").on("submit", function(e) {
      e.preventDefault(); 
      Chat.sendMessage();
   });
   
   
   $("#chat-section .chatbox #clear-messages").on("click", function() {
        Chat.clearMessages();
        Chat.fetchUnreadMsg();
   });
   
   //Notification.showMessage("Ooops! error sending your message");
   
});

const IMG_MAX_SIZE = parseInt(1048576);
const IMG_MAX_UPLOADS = parseInt(7);


Session = {
    fetchSession: function() {
        var url = "fetch_session=yes";
        $.post("ajax/session.php", url, function(e) { 
            return parseInt(e);
        });
    }
}

Notification = {
    showMessage: function($message) {
        $("#header #notification-section #notification p").html($message);
        $("#header #notification-section #notification").fadeIn("slow");
        setTimeout(function() {
           $("#header #notification-section #notification p").html("");
           $("#header #notification-section #notification").fadeOut("slow"); 
        },6000);
    }
}


Chat = {
    fetchOnlineUsers: function() {
       var url = "fetch_online_users=yes";
       $.get("ajax/chat.php", url, function(e) {
          if(e == 0) {
             $("#chat-section #online-friends ul").html("");
             var $p = $("<p />", {"class": "error"});
             $($p).text("You have no online friends");
             $("#chat-section #online-friends ul").append($p);
             return false;
          }
          
          var $data = JSON.parse(e);
          $("#chat-section #online-friends ul").html("");
           
          for(var i in $data) {
             var $id   = "friend"+$data[i].user_id;
             var $oname = ($data[i].other_name == null) ? "" : $data[i].other_name;
             var $name = $data[i].first_name+" "+$oname+" "+$data[i].last_name;
             
             var $li = $("<li />", {"id":$id});
             $($li).append($name).bind("click");
             $("#chat-section #online-friends ul").append($li);
          }
          
          $("#chat-section #online-friends ul li").on("click", function() { 
                //show the active arrow 
                $("#chat-section #online-friends ul li").removeClass("current");
                $(this).addClass("current");
                
                var $this_id = $(this).attr("id").split("friend")[1];
                $("#chatbox-wrapper .chatbox").attr("id", "chatbox"+$this_id);
                var $friend_id = $("#chatbox-wrapper .chatbox").attr("id").split("chatbox")[1];
                
                Chat.showChatBox($friend_id);
                Chat.fetchChat();
          });
       }); 
    },
    showChat: function() {
       $("#chat-wrapper","#chat-section").slideToggle("slow"); 
    },
    hideChat: function() {
       $("#chat-wrapper","#chat-section").slideToggle();
       $("#chat-section #chat-button").slideToggle("slow");
       $(window).unbind("Chat.fetchUnreadMsg()");
    },
    showChatBox: function(friend_id) {
       $("#chatbox-wrapper").show("slow");
       $("#chatbox-wrapper .chatbox").attr("id", "chatbox"+friend_id);
       setInterval(function() {
          Chat.fetchChat();
       }, 20000);
    },
    fetchChat: function() {
       var $friend_id = $("#chatbox-wrapper .chatbox").attr("id").split("chatbox")[1];
       var $url = "fetch_chat=yes&friend_id="+$friend_id;
       
       $.post("ajax/chat.php", $url, function(e) {
          $("#chat-section #chat-wrapper .message-box").html("");
          if(e == 0) return false;
          
          var $data = JSON.parse(e);
          $("#chat-section #chat-wrapper .message-box").html("");
           
          for(var i in $data) {
             if($data[i].iclass == "me") {
                var $p = $("<p />", {"class": "me"});
             }else {
                var $p = $("<p />", {"class": "other"});
             }
             
             $($p).append($data[i].message);
             $("#chat-section #chat-wrapper .message-box").append($p);
          }
          Chat.scrollToEnd();       
       }); 
    },
    sendMessage: function() {
        var $friend_id = $("#chatbox-wrapper .chatbox").attr("id").split("chatbox")[1];
        var $message = $("#chatbox-wrapper form input[type=text]").val().trim();
        
        if($message == "") return false;
        var $url = "send_message=yes&friend_id="+$friend_id+"&message="+$message;
        $.post("ajax/chat.php", $url, function(e) {
           if(e != 0) {
              var $p = $("<p />", {"class":"me"});
              $($p).append(e);
              $("#chat-section #chat-wrapper .message-box").append($p);
              Chat.scrollToEnd(); 
           } 
        });
        
        setTimeout(function() {
           Chat.fetchUnreadMsg();
        }, 9000);
    },
    scrollToEnd: function() {      
        $("#chat-section .message-box").scrollTop($("#chat-section .message-box")[0].scrollHeight);
    },
    fetchUnreadMsg: function() {
        var $friend_id = $("#chatbox-wrapper .chatbox").attr("id").split("chatbox")[1];
        
        var $url = "fetch_unread=yes&friend_id="+$friend_id;
        $.post("ajax/chat.php", $url, function(e) {
           if(e == 0) return false;
          
           var $data = JSON.parse(e);
          
           for(var i in $data) {
              if($data[i].iclass == "me") {
                var $p = $("<p />", {"class": "me"});
              }else {
                var $p = $("<p />", {"class": "other"});
              }
             
              $($p).append($data[i].message);
              $("#chat-section #chat-wrapper .message-box").append($p);
           }
           Chat.scrollToEnd(); 
        });
    },
    clearMessages: function() {
        var $friend_id = $("#chatbox-wrapper .chatbox").attr("id").split("chatbox")[1];
        var $url = "clear_messages=yes&friend_id="+$friend_id;
        
        $.post("ajax/chat.php", $url, function(e) {
            if(e == 1) {
               $("#chat-section #chat-wrapper .message-box").html(""); 
            }
        });
    }
}











