<?php
  /**
   * らんだむ
   */
  class RandomClass
  {
    public $champ = null;
    public function champ(int $length)
    {
      $tmp = [];
      for ($i=0; $i < $length; $i++) {
        $tmp[] = mt_rand(0,135);
      }
      $this->champ = $tmp;
    }
    public function build( $champ , $map = 'sf'  )
    {
      if ($map == 'sf') {
        $map_id = 11;
      }else {
        $map_id = 11;
      }
      // $data = file_get_contents('http://ddragon.leagueoflegends.com/cdn/7.12.1/data/en_US/item.json');
      $data = file_get_contents('http://ddragon.leagueoflegends.com/cdn/7.12.1/data/ja_JP/item.json');
      $data = json_decode($data,true);
      $boot_list = [];
      $item_list = [];
      foreach($data['data'] as $key => $value){
          if ( !isset($value['inStore']) && !isset($value['hideFromAll']) ){
              if (  ( $value['into'][0]) == 0  ){
                  if($map_id == 11 && $value['maps'][11]){
                    if (in_array('Trinket' , $value['tags'])) {
                      continue;
                    }
                    if (in_array('Vision' , $value['tags'])) {
                      continue;
                    }
                    if (strpos($value['name'],'エリクサー') !== false) {
                      continue;
                    }
                    if (strpos($value['name'],'ドラン') !== false) {
                      continue;
                    }
                    if (strpos($value['name'],'クイックチャージ') !== false) {
                      continue;
                    }
                    if($value['gold']['base'] == 0 && $value['gold']['total'] == 0 && $value['gold']['sell'] == 0){
                      continue;
                    }
                    if($value['stacks'] == 5){
                      continue;
                    }
                    // TODO: ハンターポーション
                    // こラフトポーション
                    // カル
                    if ($key == 2032 || $key == 2033 ||  $key == 1083) {
                      continue;
                    }
                    if ( isset($value['requiredChampion']) ) {
                      // if ($value['requiredChampion'] == "Gangplank") {
                        continue;
                      // }
                    }
                    if (in_array('Boots',$value['tags'])){
                      $boot_list[] = $key;
                    } else {
                      $item_list[] = $key;
                    }
                  }
              }
          }
      }
      $build = array_rand($item_list,5);
      $build['boots'] = array_rand($boot_list);
      foreach($build as $key => $item_id){
        if ($key !== "boots") {
          $item[$item_list[$item_id]] = $data['data'][$item_list[$item_id]];
        } else {
          $item[$boot_list[$item_id]] = $data['data'][$boot_list[$item_id]];

        }
      }
      $va = [
        "3006",
        "3047",
        "3020",
        "3158",
        "3111",
        "3117",
        "3009"
      ];
      $counter = 0;
      foreach ($va as $key => $value) {
        if (in_array($val , $item)) {
          $counter++;
        }
      }
      if ($counter > 2) {
        var_dump($build);
      }
      $this->build = $item;
    }
  }


 ?>
