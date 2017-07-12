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
    private $key = "qwertyuiopasdfghj";

    function __construct(){
        $this->get_version();
    }
    /**
     * バージョンとかサーバーとか取ってくるやつ
     * @return [type] [description]
     */
    public function get_version (){
        $url = "https://ddragon.leagueoflegends.com/realms/jp.json";
        $data = file_get_contents($url);
        // var_dump($data);
        $versions = json_decode($data , true);
        $this->version = $versions['v'];
        $this->region = $versions['l'];
    }
    public function create_random_builds($menber, $map){
      $champs = $this->random_champ($menber);
      $buld = [];
      foreach ($champs as $champ ) {
        // var_dump($this->build($champ['id'], $map));
        // exit;
        $buld[$champ['name']]['build'] = $this->build($champ['id'], $map);
        $buld[$champ['name']]['champ'] = $champ;
      }
      $this->builds = $buld;
    }
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
    public function createUrl()
    {
      $list = self::createJsonData();
      if ($list) {
        $c_t = (openssl_encrypt(gzcompress(json_encode($list),2), 'AES-128-CBC', $this->key,0,$this->key));
        $this->urlid = $c_t;
      }
    }
    public function decode($hash)
    {
      $small = $this->decodeUrl($hash);
      return $this->decodeData($small);
    }
    public function decodeUrl($hash)
    {
      $data = openssl_decrypt(($hash), 'AES-128-CBC', $this->key,0,$this->key);
      return gzuncompress($data);
    }
    public function decodeData($small){
      $small = json_decode($small,true);
      $i = 0;
      $data = file_get_contents("http://ddragon.leagueoflegends.com/cdn/{$this->version}/data/{$this->region}/item.json");
      $ori_item = json_decode($data,true);
      $url = "http://ddragon.leagueoflegends.com/cdn/{$this->version}/data/{$this->region}/champion.json";
      $data = file_get_contents($url);
      $ori_champ = json_decode($data,true);
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
          // var_dump($it);exit;
          $arr[$k] =[
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
        $url = "http://ddragon.leagueoflegends.com/cdn/{$this->version}/data/{$this->region}/champion.json";
        $data = file_get_contents($url);
        $data = json_decode($data,true);
        $this->ori_champ = $data;
        $d = array_rand($data['data'],$menber);
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
        $map_id = 11;
      }
      $data = file_get_contents("http://ddragon.leagueoflegends.com/cdn/{$this->version}/data/{$this->region}/item.json");
      $data = json_decode($data,true);
      $boot_list = [];
      $item_list = [];
      foreach($data['data'] as $key => $value){
          if ( !isset($value['inStore']) && !isset($value['hideFromAll']) ){
              if ( !isset($value['into']) || ( $value['into'][0]) == 0  ){
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
      // $boots = [
      //   "3006",
      //   "3047",
      //   "3020",
      //   "3158",
      //   "3111",
      //   "3117",
      //   "3009"
      // ];
      // $counter = 0;
      // foreach ($boots as $key => $value) {
      //   if (in_array($val , $item)) {
      //     $counter++;
      //   }
      // }
      // if ($counter > 2) {
      //   var_dump($build);
      // }
      return $item;
    }
  }


 ?>
