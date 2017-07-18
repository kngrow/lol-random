<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>らんだむ</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/champ.css">
  </head>
  <body>
    <div class="wrapper">
        <h1> ランダムアイテムセレクター</h1>
        <p>
           あなたのチャンピオンとビルドを勝手に決めます。<br>
            <span class="red">trollプレイでreportされても一切責任を負いません<span>
        </p>
        <form action="create_builds.php" method="POST">
            <div>
                <select name="menber">
                    <?php for($i = 1 ; $i <= 10 ; $i++): ?>
                    <option><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label><input type="radio" name="map" value="sf" checked="checked">summoners lift</label>
                <label><input type="radio" name="map" value="ha" >howling abyss</label>

            </div>
            <button type ="submit">build!</button>
        </form>
    </div>
 </body>
</html>
