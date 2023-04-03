(function ($) {
  $(document).ready(function(){
    $('#billing_company_field').hide();
    $('#tax_id_field').hide();

    $('#checkbox_options').click(function(){
      if($(this).prop("checked") == true){
        $('#billing_company_field').show();
        $('#tax_id_field').show();

      }
      else if($(this).prop("checked") == false){
        $('#billing_company_field').hide();
        $('#tax_id_field').hide();
      }
    });
  });


})(jQuery);
