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
  var clipboard = new Clipboard('.btn');
});
