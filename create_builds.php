<?php

require './RandomClass.php';

$rc = new RandomClass();
if (isset($_GET['hash'])) {
  $random_list = $rc->decode($_GET['hash']);
  $url = $_GET['hash'];
} else if( isset( $_POST['menber'] ) && isset( $_POST['map'] ) ) {
// } else {
    $number = $_POST['menber'] + 0;
    $map = $_POST['map'];
    if( is_numeric( $number ) && ($number > 0 && $number <= 10 ) && ( $map == 'sf' || $map == 'ha'  ) ){
      $rc->create_random_builds($number,$map);
      // $rc->create_random_builds(4,'sf');
      $rc->createJsonData();
      $rc->createUrl();
      $random_list = $rc->builds;
      $url = $rc->urlid;
    }
}
// echo json_encode($random_list);exit;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
<textarea  rows="8" cols="80">http://lol-random.kngrow.me/create_builds.php?hash=<?= urlencode($url)  ?>
</textarea>
<hr>

<?php if($random_list): ?>
<?php foreach ($random_list as $name => $champ): ?>
  <div class="builds">
    <div class="champ">
      <img src="http://ddragon.leagueoflegends.com/cdn/<?= $rc->version ?>/img/champion/<?= $champ['champ']['image']['full'] ?>" alt="">
      <span><?= $name ?></span>
    </div>
    <?php foreach ($champ['build'] as $key => $value): ?>
      <div class="items" style="display  : inline-block; width:15%">
      <div style="height:<?= $value['image']['h'] ?>px; width:<?= $value['image']['w'] ?>px; background: url('//ddragon.leagueoflegends.com/cdn/<?= $rc->version ?>/img/sprite/<?= $value['image']['sprite'] ?>') -<?= $value['image']['x'] ?>px -<?= $value['image']['y'] ?>px no-repeat;">
      </div>
      <span><?= $value['name'] ?></span>
      <p>
        <?php foreach ($value['from'] as $k => $v): ?>
          <img src="http://ddragon.leagueoflegends.com/cdn/<?= $rc->version ?>/img/item/<?= $v ?>.png" style="width:40px" >

        <?php endforeach; ?>
      </p>
    </div>
    <?php endforeach; ?>
  </div>
  <hr>
<?php endforeach; ?>
<?php else: ?>
存在しないデータっす 
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
</script>
</body>
</html>
