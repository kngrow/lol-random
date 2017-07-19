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
            <div class="form_parts">
                <h3><label for="member"> 人数 </label></h3>
                <select name="menber" id="menber">
                    <?php for($i = 1 ; $i <= 10 ; $i++): ?>
                    <option><?= $i ?></option>
                    <?php endfor; ?>
                </select>
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
</html>
