$(function() {
    $("#fname").blur(function() {
        validate_first_name("name", $("#fname").val(), "fname");
    });
    
    $("#lname").blur(function() {
        validate_first_name("name", $("#lname").val(), "lname");
    });
    
    $("#email").blur(function() {
        validate_first_name("email", $("#lname").val(), "lname");
    });
    
    function validate_first_name(column_name, value, input_id) {
        $.ajax({
            type: "POST",
            url:  "../ajax/validate.php",
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