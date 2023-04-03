(function ($){

  $('#user_activity_feed').on('submit',function (event){
    event.preventDefault();
    $.ajax({
      url: user_narrative.save_narrative_url,
      type: 'post',
      data: $(this).serialize(),

      success:function(response){

        $( '#user_activity_feed' ).each(function(){
          this.reset();
        });
        $('#message').html(response);
        // setTimeout(function() {
        //   $('#message').hide();
        // }, 2000);
        },
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status == 500) {

          $("#message").html('Please Fill All Required fields');
        } else {

          $("#message").html('Unexpected error');
        }
      }

    });
  })


})(jQuery)

