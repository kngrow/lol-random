<?php
require './RandomClass.php';
$rc = new RandomClass();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>らんだむ</title>
    <link rel="stylesheet" href="/css/top.css">
  </head>
  <body>
    <div class="wrapper">
        <h1> ランダムアイテムセレクター</h1>
        <p>
           あなたのチャンピオンとビルドを勝手に決めます。<br>
            <span class="red">trollプレイでreportされても一切責任を負いません<span>
        </p>
        <form action="create_builds.php" method="POST">
             <div id="tab">
              <ul>
                <li ><a href="#menber">人数選択(championとitemがランダム)</a></li>
                <li ><a href="#champ">champion選択(itemがランダム)</a></li>
              </ul>
              <div class="form_parts" id="menber">
                  <h3><label for="member"> 人数 </label></h3>
                  <select name="menber" id="menber">
                      <?php for($i = 1 ; $i <= 10 ; $i++): ?>
                      <option><?= $i ?></option>
                      <?php endfor; ?>
                  </select>
              </div>
              <div class="form_parts" id="champ">
                <div class="champ_list">
                  <div class="champ_select_wrapper">
                    <select name="champs[]" id="champselector">
                      <?php foreach($rc->ori_champ['data'] as $key => $value): ?>
                         <option value="<?= $key ?>"><?= $value['name'] ?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
               </div>
                  <button type="button" class="add_menber">追加</button>
              <p class="champ_select_warning hide">１０チャンプ以上は選べないンゴ</p>
              </div>
            </div>
      
            <div class="form_parts">
                <h3>map</h3>
                <label><input type="radio" name="map" value="sf" checked="checked">summoners lift</label>
                <label><input type="radio" name="map" value="ha" >howling abyss</label>

            </div>
            <div class="submit_wrapper">
                <button type ="submit">build!</button>
            </div>
        </form>
    </div>
 </body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tabslet.js/1.7.3/jquery.tabslet.min.js"></script>
<script src="js/top.js"></script>
</html>
