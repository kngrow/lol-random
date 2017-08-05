<?php
require './RandomClass.php';
$rc = new RandomClass();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#1b3042">
    <meta name="viewport" content="width=device-width" />
    <title>らんだむ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/green.css">
    <link rel="stylesheet" href="/css/top.css">
  </head>
  <body>
    <div class="wrapper">
      <div class="text">
        <h1> f**k build generator</h1>
        <p>
          チャンピオンとビルドを勝手に決めます。<br>
          NormalやARAMに飽きた時、決められたチャンピオンとアイテムで紅白戦を行うと新鮮です<br>
          <span class="red bold">trollプレイでreportされても一切責任を負いません<span>
        </p>
      </div>
      <form action="create_builds.php" method="POST">
         <div id="tab">
           <ul>
             <li><a href="#menber">チャンプ/アイテム ランダム</a></li>
             <li><a href="#champ">アイテムランダム</a></li>
           </ul>
           <div class="form_parts" id="menber">
              <p class="howto">チャンピオンを選択できるゲームモードはこちら！<br>表示されたチャンピオンをピックしてください</p>
             <h3><label for="member"> 人数 </label></h3>
             <select name="menber" id="menber">
               <option value="2">1 vs 1</option>
               <option value="4">2 vs 2</option>
               <option value="6">3 vs 3</option>
               <option value="8">4 vs 4</option>
               <option value="10" selected>5 vs 5</option>
             </select>
           </div>
           <div class="form_parts" id="champ">
           <p class="howto">チャンピオンが勝手に選ばれるゲームモードはこちら!<br>ゲーム開始時に表示されたチャンピオンを選択してください</p>
           <h3> 選択champion追加 </h3>
           <button type="button" class="add_menber">人数追加</button>
              <div class="champ_list">
                <div class="left">
                  <div class="champ_select_wrapper">
                    <select name="champs[]" class="champselector" tabindex='1'>
                      <option value=""></option>
                      <?php foreach($rc->ori_champ['data'] as $key => $value): ?>
                      <option value="<?= $key ?>"><?= $value['name'] ?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                </div>
                <div class="right">
                <div class="champ_select_wrapper">
                   <select name="champs[]" class="champselector" tabindex='1'>
                     <option value=""></option>
                     <?php foreach($rc->ori_champ['data'] as $key => $value): ?>
                     <option value="<?= $key ?>"><?= $value['name'] ?></option>
                     <?php endforeach;?>
                     </select>
                </div>
             </div>
           </div>
           <p class="champ_select_warning hide">１０チャンプ以上は選べないンゴ</p>
         </div>
       </div>
       <div class="form_parts">
          <h3>map</h3>
        <div class="map_wrapper">
          <label><input type="radio" name="map" value="sf" checked="checked">summoners lift</label>
          <label><input type="radio" name="map" value="ha" >howling abyss</label>
         </div>
       </div>
       <div class="submit_wrapper">
         <button type ="submit">build!</button>
       </div>
     </form>
    </div>
  <footer>
    <span>Copyright (c) 2017 Copyright pinon All Rights Reserved.</span>
</footer>
 </body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tabslet.js/1.7.3/jquery.tabslet.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
<script src="js/top.js"></script>
</html>
