$(function(){
  $('#tab').tabslet();
  $('#champ').find('select').prop('disabled',true);
  $('input').iCheck({
      radioClass: 'iradio_square-green',
  });
  $('#tab').on("_before" ,function(e){
      var active = $(e.target).find('a').attr('href');
      if (active == '#champ'){
        $('.champselector').prop('disabled',false);
        $('select#menber').prop('disabled',true);
      } else {
        $('.champselector').prop('disabled',true);
        $('select#menber').prop('disabled',false);
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

      $(".champselector").select2({
       placeholder: "選択してくれ　　　　",
       width: '80%',
      });

  $('.add_menber').on('click',function(){
      // var select_champ = $('.champ_select_wrapper:last-child option:selected').val();
      $wrapper_count = $('.champ_select_wrapper').length
      if( $wrapper_count < 10  ){
        $('.left .champ_select_wrapper:first-child .champselector').select2('destroy');
        var $champ_select_wrapper = $('.left .champ_select_wrapper:first-child').clone(true);
        var $champ_select_right_wrapper = $('.left .champ_select_wrapper:first-child').clone(true);
        $('.champ_list .left').append($champ_select_wrapper);
        $('.champ_list .right').append($champ_select_right_wrapper);
        $(".champselector").select2({
          placeholder: "選択してくれ　　　　",
          width: '80%',
        });
        // $champ_select_wrapper.find('option[value='+ select_champ +']').remove();
      }
  });
});
