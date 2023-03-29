$(document).ready(function(){
    $("#form").submit(function(e) {
      e.preventDefault(); // avoid to execute the actual submit of the form.
      var form = $(this);
      var actionUrl = form.attr('action');
      $.ajax({
          type: "GET",
          url: actionUrl,
          data: form.serialize(), // serializes the form's elements.
          success: function(data)
          {
            
              if(data["estRediriger"]==true){
                document.location.href=data["redirection"]+".html";
              }else{
                $("#lblMessage").html(
                    " <div class='alert alert-" +
                    data['status'] +
                    "'  role='alert'>" +
                    data['message'] +
                    "</div>");
              }
          }
          
      });
      
    });
  });
  
  