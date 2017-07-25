$(function(){
  $('#tab').tabslet();
  $('#champ').find('input').prop('disabled',true);
  $('#tab').on("_before" ,function(){
      var active = $("#tab .active a").text();
      if (active == 'champ'){
        $('#champ').find('input').prop('disabled',true);
        $('select#menber').prop('disabled',false);
      } else {
        $('#champ').find('input').prop('disabled',false);
        $('select#menber').prop('disabled',true);
      }
  });
  $('input.champ').on('click', function(){
    $champ_selecter = $('input.champ:checked').length;
    if($champ_selecter >= 11){
       $(this).prop('checked',false);
       $('.champ_select_warning').show();
    } else{
       $('.champ_select_warning').hide();
    }
  });
  $('.add_menber').on('click',function(){
      // var select_champ = $('.champ_select_wrapper:last-child option:selected').val();
      $wrapper_count = $('.champ_select_wrapper').length
      if( $wrapper_count < 10  ){
        var $champ_select_wrapper = $('.champ_select_wrapper:last-child').clone();
        // $champ_select_wrapper.find('option[value='+ select_champ +']').remove();
        //
        if( $wrapper_count == 5  ){
          $('.champ_list').append('<hr>');
        }
        $champ_select_wrapper.appendTo('.champ_list');
      }
  });
});
