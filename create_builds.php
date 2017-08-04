<?php

require './RandomClass.php';

$rc = new RandomClass();
if (isset($_GET['hash'])) {
  $random_list = $rc->decode($_GET['hash']);
  $url = $_GET['hash'];
} else if( ( isset($_POST['champs']) ||  isset( $_POST['menber'] ) ) && isset( $_POST['map'] ) ) {
  $number = isset($_POST['menber']) ? $_POST['menber'] + 0 : null;
  $map = $_POST['map'];
  $champs = isset($_POST['champs']) ? $_POST['champs'] : null;
    if( ( count($champs) > 0 && count($champs) <= 10 ) || ( is_numeric( $number ) && ($number > 0 && $number <= 10 ) ) && ( $map == 'sf' || $map == 'ha'  ) ){
      $rc->createRandomBuilds($number,$map,$champs);
      $rc->createJsonData();
      $rc->createUrl();
      $random_list = $rc->builds;
      $url = $rc->urlid;
      $half = ( count($random_list) / 2 ) - 1;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>100%の確率でランダムなビルドを出すサモリフbot</title>
  <meta name="viewport" content="width=device-width" />
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="build-wrapper">
  <textarea id="url" rows="8" cols="80" readonly="readonly">http://lol-random.kngrow.me/create_builds.php?hash=<?= urlencode($url)  ?></textarea>
  <button class="btn" data-clipboard-target="#url" data-copied-hint="Copied!" > copy!</button>
  <a href="/"> もどる</a>

  <?php if($random_list): ?>
  <?php foreach ($random_list as $menber => $champ): ?>
  <div class="builds <?= $menber > $half ? 'red_team' : '' ?>">
      <div class="champ">
        <img src="http://ddragon.leagueoflegends.com/cdn/<?= $rc->version ?>/img/champion/<?= $champ['champ']['image']['full'] ?>" alt="">
        <span class="champ-name"><?= $champ['champ']['name'] ?></span>
      </div>
      <div class="item-list">
        <?php foreach ($champ['build'] as $key => $value): ?>
          <div class="items">
          <div class="item-img" style="height:<?= $value['image']['h'] ?>px; width:<?= $value['image']['w'] ?>px; background: url('//ddragon.leagueoflegends.com/cdn/<?= $rc->version ?>/img/sprite/<?= $value['image']['sprite'] ?>') -<?= $value['image']['x'] ?>px -<?=   $value['image']['y'] ?>px no-repeat;">
          </div>
          <span class="name"><?= $value['name'] ?></span>
          <!--
          <p>
            <?php foreach ($value['from'] as $k => $v): ?>
              <img src="http://ddragon.leagueoflegends.com/cdn/<?= $rc->version ?>/img/item/<?= $v ?>.png" style="width:40px" >

            <?php endforeach; ?>
          </p>
          -->
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php if($half === $menber): ?>
    <hr>
    <?php endif; ?>
  <?php endforeach; ?>
  <?php else: ?>
  <p>存在しないデータっす</p>
  <?php endif; ?>

  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
  <script src="./js/script.js"></script>
  </div>
</body>
</html>
