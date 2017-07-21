<?php
  /**
   * らんだむ
   */
  class RandomClass
  {
    public $champ = null;
    public $builds = null;
    public $version ;
    public $region ;
    // 別に見られても問題ナッシング
    private $key = "qawsedrftgyhujikolp";

    function __construct(){
        $this->get_version();
        $this->get_data();
    }
    /**
     * バージョンとかサーバーとか取ってくるやつ
     * @return [type] [description]
     */
    public function get_version (){
        $url = "https://ddragon.leagueoflegends.com/realms/jp.json";
        $data = file_get_contents($url);
        $versions = json_decode($data , true);
        $this->version = $versions['v'];
        $this->region = $versions['l'];
    }
    /**
     * APIからデータを取ってきて置いとく
     */
    public function get_data(){
      $data = file_get_contents("http://ddragon.leagueoflegends.com/cdn/{$this->version}/data/{$this->region}/item.json");
      $this->ori_item = json_decode($data,true);
      $url = "http://ddragon.leagueoflegends.com/cdn/{$this->version}/data/{$this->region}/champion.json";
      $data = file_get_contents($url);
      $this->ori_champ = json_decode($data,true);
    }
    /**
     * メンバーとマップを選んでランダムにするやつ（大元
     */
    public function create_random_builds($menber = null, $map, $select_champ = null){
      if( $menber > 0 && is_null($select_champ)){
        $champs = $this->random_champ($menber);
      } else {
        $champs = $this->create_champ_data($select_champ);
      }
      // var_dump($champs);;exit;
      $buld = [];
      foreach ($champs as $champ ) {
        $buld[$champ['name']]['build'] = $this->build($champ['id'], $map);
        $buld[$champ['name']]['champ'] = $champ;
      }
      $this->builds = $buld;
    }
    /**
     * 作ったランダムのリストからhashにさせるjsonのリストを作成する
     */
    public function createJsonData()
    {
      $build = $this->builds;
      if ($build) {
        $list = [];
        foreach ($build as $key => $value) {
          $items = array_column($value['build'],'id');
          $list[$value['champ']['id']] = $items;
        }
        return $list;
      }
      return false;
    }
    /**
     * 作ったbuildsのjsondata から共有用のhashを作成する
     */
    public function createUrl()
    {
      $list = $this->createJsonData();
      if ($list) {
        $c_t = (openssl_encrypt(gzcompress(json_encode($list),2), 'AES-128-CBC', $this->key,0,$this->key));
        $this->urlid = $c_t;
      }
    }
    /**
     * hashからjsonにもどして、データをもどすやつ
     */
    public function decode($hash)
    {
      $small = $this->decodeUrl($hash);
      if($small){
          return $this->decodeData($small);
      } else {
          return false;
      }
    }
    public function decodeUrl($hash)
    {
      $data = openssl_decrypt(($hash), 'AES-128-CBC', $this->key,0,$this->key);
      return gzuncompress($data);
    }
    public function decodeData($small){
      $small = json_decode($small,true);
      $i = 0;
      $ori_item = $this->ori_item;
      $ori_champ = $this->ori_champ;
      foreach ($small as $champ => $item) {
        $cm = $ori_champ['data'][$champ];
        $list[$cm['name']]['champ'] = [
          'name' => $cm['name'],
          'id' => $cm['id'],
          'image' => $cm['image']
        ];
        $arr = [];
        foreach ($item as $k) {
          $it = $ori_item['data'][$k];
          $arr[$k] = [
                  'name' => $it['name'],
                  'id' => $it[$item_id],
                  'from' => $it['from'],
                  'image' => $it['image'],
                ];
        }
        $list[$cm['name']]['build'] = $arr;
        $i++;
      }
      return $list;
    }
    /**
     * ランダムでチャンピオン選ぶ奴
     * @param  integer $menber [description]
     * @return [type]          [description]
     */
    public function random_champ($menber = 5){
        $data = $this->ori_champ;
        $d = array_rand($data['data'],$menber);
        if( ! is_array($d) ){
            $tmp = [];
            $tmp[] = $d;
            $d = $tmp;
        }
        $champs = [];
        foreach ($d as $key => $value) {
          $cm = $data['data'][$value];
          $arr = [
            'name' => $cm['name'],
            'id' => $cm['id'],
            'image' => $cm['image'],
          ];
          $champs[] = $arr;
        }
        return $champs;
    }
    public function create_champ_data($select_champ){
        if( ! is_array($select_champ) ){
            $tmp = [];
            $tmp[] = $select_champ;
            $select_champ = $tmp;
        }
        $champs = [];
        $data = $this->ori_champ;
        foreach ($select_champ as $key => $value) {
          $cm = $data['data'][$value];
          $arr = [
            'name' => $cm['name'],
            'id' => $cm['id'],
            'image' => $cm['image'],
          ];
          $champs[] = $arr;
        }
        return $champs;
    }
    /**
     * ビルド奴
     * @param  [type] $champ [description]
     * @param  string $map   [description]
     * @return [type]        [description]
     */
    public function build( $champ , $map = 'sf'  )
    {
      if ($map == 'sf') {
        $map_id = 11;
      }else {
        $map_id = 12;
      }
      $data = $this->ori_item;
      $boot_list = [];
      $item_list = [];
      foreach($data['data'] as $key => $value){
          if ( !isset($value['inStore']) && !isset($value['hideFromAll']) ){
              if ( !isset($value['into']) || ( $value['into'][0]) == 0  ){
                  if($map_id === 11 ){
                    if ( !$value['maps'][11]  ) {
                      continue;
                    }
                  }
                  if($map_id === 12 ){
                    if ( !$value['maps'][12]  ) {
                      continue;
                    }
                  }
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
                  if(isset($value['stacks'])){
                    continue;
                  }
                  // TODO: ハンターポーション
                  // こラフトポーション
                  // カル
                  if ($key == 2032 || $key == 2033 ||  $key == 1083) {
                    continue;
                  }
                  if ( isset($value['requiredChampion']) ) {
                      continue;
                  }
                  if (in_array('Boots',$value['tags'])){
                    $boot_list[] = $key;
                  } else {
                    $item_list[] = $key;
                  }
              }
          }
      }
      if($champ === 'Victor'){
          $build = array_rand($item_list,4);
          $tmp = $data['data'][3198];
          $arr = [
            'name' => $tmp['name'],
            'id' => $item_list[$item_id],
            'from' => $tmp['from'],
            'image' => $tmp['image'],
          ];
          $item[$item_list[$item_id]] = $arr;
      } else if($champ === 'Cassiopeia'){
          $build = array_rand($item_list,6);
      } else {
          $build = array_rand($item_list,5);
      }
      if($champ !== 'Cassiopeia'){
          $build['boots'] = array_rand($boot_list);
      }
      foreach($build as $k => $item_id){
        if ($k === "boots") {
          $boots = $data['data'][$boot_list[$item_id]];
          $arr = [
            'name' => $boots['name'],
            'id' => $boot_list[$item_id],
            'from' => $boots['from'],
            'image' => $boots['image'],
          ];
          $item[$boot_list[$item_id]] = $arr;
        } else {
          $tmp = $data['data'][$item_list[$item_id]];
          $arr = [
            'name' => $tmp['name'],
            'id' => $item_list[$item_id],
            'from' => $tmp['from'],
            'image' => $tmp['image'],
          ];
          $item[$item_list[$item_id]] = $arr;
        }
      }
     return $item;
    }
  }
