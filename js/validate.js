$(function() {
    $("#fname").blur(function() {
        validate("name", $("#fname").val(), "fname");
    });
    
    $("#lname").blur(function() {
        validate("name", $("#lname").val(), "lname");
    });
    
    $("#email").blur(function() {
        validate("email", $("#email").val(), "email");
    });
    
    $("#password").blur(function() {
        validate("password", $("#password").val(), "password");
    });
    
    function validate(column_name, value, input_id) {
        $.ajax({
            type: "POST",
            url:  "ajax/validate.php",
            data: column_name+"="+value+ "&value="+value,
            success: function(e) {
                       if(e == 1) {
                          $("#"+input_id).removeClass("invalid").addClass("valid");
                       }else {
                          $("#"+input_id).removeClass("valid").addClass("invalid");
                       }
                     },
            error: function() {
                alert("Error making connection");
            }
        });
    }
});