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
        $this->getVersion();
        $this->getData();
    }
    /**
     * バージョンとかサーバーとか取ってくるやつ
     * @return [type] [description]
     */
    public function getVersion (){
        $url = "https://ddragon.leagueoflegends.com/realms/jp.json";
        $data = file_get_contents($url);
        $versions = json_decode($data , true);
        $this->version = $versions['v'];
        $this->region = $versions['l'];
    }
    /**
     * APIからデータを取ってきて置いとく
     */
    public function getData(){
      $version = json_decode(@file_get_contents('./data/version.json'));
      if($version[0] !== $this->version ){
        // echo "new";
        $data = file_get_contents("http://ddragon.leagueoflegends.com/cdn/{$this->version}/data/{$this->region}/item.json");
        file_put_contents('./data/item.json',$data);
        $this->ori_item = json_decode($data,true);

        $url = "http://ddragon.leagueoflegends.com/cdn/{$this->version}/data/{$this->region}/champion.json";
        $data = file_get_contents($url);
        file_put_contents('./data/champ.json',$data);
        $this->ori_champ = json_decode($data,true);

        file_put_contents('./data/version.json',json_encode([$this->version, $this->region]));
      } else {
        // echo "read";
        $this->ori_item = json_decode(file_get_contents('./data/item.json'),true);
        $this->ori_champ = json_decode(file_get_contents('./data/champ.json'),true);
      }
    }
    /**
     * メンバーとマップを選んでランダムにするやつ（大元
     */
    public function createRandomBuilds($menber = null, $map, $select_champ = null){
      if( $menber > 0 && is_null($select_champ)){
        $champs = $this->randomChamp($menber);
      } else {
        $champs = $this->createChampData($select_champ);
      }
      $buld = [];
      foreach ($champs as $key => $champ ) {
        $buld[$key]['build'] = $this->build($champ['id'], $map);
        $buld[$key]['champ'] = $champ;
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
    /**
     * URLをデコードする
     * @param  [type] $hash [description]
     * @return [type]       [description]
     */
    public function decodeUrl($hash)
    {
      $data = openssl_decrypt(($hash), 'AES-128-CBC', $this->key,0,$this->key);
      return gzuncompress($data);
    }
    /**
     * Hashをデコードしてデータにする
     * @param  [type] $small [description]
     * @return [type]        [description]
     */
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
    public function randomChamp($menber = 5, $is_overlap = false){
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
    /**
     * PostされたChampリストをデータにあてはめる
     * @param  [type] $select_champ [description]
     * @return [type]               [description]
     */
    public function createChampData($select_champ){
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
      } else {
        $map_id = 12;
      }
      $data = $this->ori_item;
      $boot_list = [];
      $item_list = [];
      foreach($data['data'] as $key => $value){
        if ( !isset($value['inStore']) && !isset($value['hideFromAll']) ){
          if ( !isset($value['into']) || ( $value['into'][0]) == 0  ){
            // サモナーズリフト
            if( $map_id === 11 ){
              if ( !$value['maps'][11] ) {
                continue;
              }
            }
            // ハウリングアビス
            if( $map_id === 12 ){
              if ( !$value['maps'][12] ) {
                continue;
              }
              if ( in_array('GoldPer', $value['tags']) ){
                continue;
              }
              // GA,メジャイ,オームレッカー
              if ( in_array($key , ['3026', '3041', '3056' ]) ){
                continue;
              }
            }
            if (in_array('Trinket', $value['tags'])) {
              continue;
            }
            if (in_array('Vision', $value['tags'])) {
              continue;
            }
            if (strpos($value['name'], 'エリクサー') !== false || strpos($value['name'], 'ドラン') !== false || strpos($value['name'], 'クイックチャージ') !== false) {
              continue;
            }
            if($value['gold']['base'] == 0 && $value['gold']['total'] == 0 && $value['gold']['sell'] == 0){
              continue;
            }
            if(isset($value['stacks'])){
              continue;
            }
            // ハンターポーション, こラフトポーション, カル
            if( in_array($key , [2032, 2033, 1083]) ){
              continue;
            }
            if ( isset($value['requiredChampion']) ) {
                continue;
            }
            if (in_array('Boots', $value['tags'])){
              $boot_list[] = $key;
            } else {
              $item_list[] = $key;
            }
          }
        }
      }
      if($champ === 'Viktor'){
        $build = array_rand($item_list, 4);
        $tmp = $data['data'][3198];
        $arr = [
          'name' => $tmp['name'],
          'id' => 3198,
          'from' => $tmp['from'],
          'image' => $tmp['image'],
        ];
        $item[3198] = $arr;
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
          // meleeチャンプはhurrycaneを詰めないので再抽選？
          if( $this->is_melee($champ) && $item_list[$item_id] == 3085 ){
            $item_id = $this->withoutHurrycaneRottely($item_list);
          }
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
    public function lottey(){

    }
  /**
   * rangedかmeleeか判定（多分
   * @param string $champ_id
   * @return boolean  melee ならtrue
   */
  public function is_melee($champ_id){
    $champ_data = $this->ori_champ['data'][$champ_id];
    return $champ_data['stats']['attackrange'] <= 175 ? true : false;
  }
  /** 
   * ハリケーンなしで１個返す
   * @param array $item_list 
   * @return int アイテムの配列の順番
   */
  public function withoutHurrycaneRottely($item_list){
    $hurricane_key = array_search(3085, $item_list);
    unset($item_list[$hurricane_key]);
    return array_rand($item_list);
  }
}
