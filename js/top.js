$(function(){
  $('#tab').tabslet();
  $('#champ').find('select').prop('disabled',true);
  $('#tab').on("_before" ,function(){
      var active = $("#tab .active a").text();
      if (active == 'champ'){
        $('#champ').find('select').prop('disabled',true);
        $('select#menber').prop('disabled',false);
      } else {
        $('#champ').find('select').prop('disabled',false);
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

  $('.champselector').scombobox({
    fullMatch:true});

  $('.add_menber').on('click',function(){
      // var select_champ = $('.champ_select_wrapper:last-child option:selected').val();
      $wrapper_count = $('.champ_select_wrapper').length
      if( $wrapper_count < 10  ){
        var $champ_select_wrapper = $('.left .champ_select_wrapper:first-child').clone(true);
        if($('.left .champ_select_wrapper').length > $('.right .champ_select_wrapper').length){
          $champ_select_wrapper.appendTo('.champ_list .right');
        } else {
          $champ_select_wrapper.appendTo('.champ_list .left');
        }
        // $champ_select_wrapper.find('option[value='+ select_champ +']').remove();
      }
  });
});
