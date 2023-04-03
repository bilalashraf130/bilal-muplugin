(function ($) {
  $(document).ready(function (){
    $('#learndash-page-content > div > div > div.learndash_content_wrap > div.ld-content-actions > div > form > input.learndash_mark_complete_button').hide();
    $("#bonus_certificate").on("submit", function (event) {
      event.preventDefault();
      var formData = new FormData(this);
      $("#loading").show();
      $('#info').hide();
      $.ajax({
        url: bonus_certificate.get_bonus_certificate_data,
        type: "post",
        data: formData,
        contentType: false,
        processData: false,

        success: function (response) {
          console.log(response);
          var status = response.trim();
          if(status == 'done'){
            $('#learndash-page-content > div > div > div.learndash_content_wrap > div.ld-content-actions > div > form > input.learndash_mark_complete_button').click();
          }

        },
        error: function (jqXHR, textStatus, errorThrown) {
          if (jqXHR.status == 500) {
            $('#info').html('Please Fill All Fields').show();
          } else {
            console.log('unexpected error');
          }

          $("#loading").hide();
        }
      });


    });
///////upload button


    jQuery(".uploadlogo").change(function() {
      var filename = readURL(this);
      jQuery(this).parent().children('span').html(filename);
    });

    // Read File and return value
    function readURL(input) {
      var url = input.value;
      var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
      if (input.files && input.files[0] && (
        ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "gif"
      )) {
        var path = jQuery(input).val();
        var filename = path.replace(/^.*\\/, "");
        return "Uploaded file : "+filename;
      } else {
        jQuery(input).val("");
        return "Only image formats are allowed!";
      }
    }



  });


})(jQuery);
