<?php

require './Encryption.php';
require './RandomClass.php';

$converter = new Encryption;
$encoded = $converter->encode(serialize($str));
$decoded = $converter->decode($encoded);

// var_dump($encoded);

echo "<hr>";

$rc = new RandomClass();
$rc->champ(4);
$rc->build('aa');

// var_dump($rc->build);

?>


<?php foreach ($rc->build as $key => $value): ?>
  <div class="" style="width:100%">
    <!-- <img src="http://ddragon.leagueoflegends.com/cdn/7.12.1/img/item/<?= $key ?>.png" alt="<?= $value['name'] ?>"> -->
    <div style="height:<?= $value['image']['h'] ?>px; width:<?= $value['image']['w'] ?>px; background: url('//ddragon.leagueoflegends.com/cdn/7.12.1/img/sprite/<?= $value['image']['sprite'] ?>') -<?= $value['image']['x'] ?>px -<?= $value['image']['y'] ?>px no-repeat;">

    </div>
    <span><?= $value['name'] ?></span>
    <p>
      <?php foreach ($value['from'] as $k => $v): ?>
        <img src="http://ddragon.leagueoflegends.com/cdn/7.12.1/img/item/<?= $v ?>.png" style="width:40px" >

      <?php endforeach; ?>
    </p>
  </div>
<?php endforeach; ?>
