<?php
ini_set("display_errors", 1);

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
function create_json(){
    global $wpdb;
    $and = '';
    $for_hash = '';
    foreach ($_GET as $k => $g){
        if(strpos($k,'_sfm_') !== false){
            $param = explode('_sfm_', $k);
            $p = $param[1];
            $v = $g;
            $and .= "AND $wpdb->postmeta.post_id in(SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '{$p}' AND meta_value LIKE '%{$v}%') ";
            $for_hash .= $g;
        }
    }
    if(isset($_GET['_sft_post_tag'])){
        $id_tag = get_terms([
            'taxonomy' => 'post_tag',
            'slug' => $_GET['_sft_post_tag']
        ]);

        $sql_query = "SELECT * from wp_search_filter_term_results where field_value={$id_tag[0]->term_taxonomy_id}";
        $result =  $wpdb->get_results($sql_query,ARRAY_A);
        $ids = explode(',', $result[0]['result_ids']);

        $and .= "  
    AND $wpdb->posts.id in({$result[0]['result_ids']})";
        $for_hash .= $_GET['_sft_post_tag'];
    }
    $sql_query = "SELECT $wpdb->posts.id, $wpdb->posts.post_content, $wpdb->posts.post_title 
    from $wpdb->postmeta, $wpdb->posts WHERE  
    $wpdb->postmeta.post_id = $wpdb->posts.id 
    {$and}
    AND $wpdb->posts.post_status = 'publish'
    AND $wpdb->posts.post_type='post' GROUP BY $wpdb->posts.id";
    $posts =  $wpdb->get_results($sql_query);

    $j_coor = [];
    $titles = [];
    $images = [];
    foreach ($posts as $i => $p){
        $d = $p->post_content;
        $f = explode('center="', $d);
        $f2 = explode('"', $f[1]);
        $f3 = explode(',', $f2[0]);
        $lat = trim($f3[0]);
        $lon = trim($f3[1]);

        //парсим тип рыбы для нижней метки что у нас на фото (решил бобнусом добавить, если что удалю).
        $t = explode('Тип рыбы на фото (предполагаемый):</strong>', $d);
        $t2 = explode('</p>', $t[1]);
        $type_fish = rtrim($t2[0], '.');
        $pt = explode("—", $p->post_title);
        $j_coor[] = [$lat, $lon];
        $titles[] = ['id' => $p->id,'title' => $pt[0], 'type_fish' => $type_fish, 'href' =>'/'.$p->id.'/'];// get_bloginfo('wpurl').
    }
    $data = ['type' => 'FeatureCollection', 'features' => []];
    foreach ($j_coor as $key => $coor){
        $type_fish = ($titles[$key]['type_fish'] != '') ? $titles[$key]['type_fish'] : "";
        $features = [
            'type' => 'Feature',
            'id' => $titles[$key]['id'],
            'geometry' => [
                'type' =>   'Point',
                'coordinates' => $coor
            ],
            'properties' => [
                'balloonContentHeader' => "<h2 class=ballon_header>{$titles[$key]['title']}</h2>",
                'balloonContentBody' =>  "<div class=ballon_body><div>".
                    "<a href='{$titles[$key]['href']}' target='_blank'>
<img loading='lazy' src='/wp-content/plugins/fishing-report-yandex-map/crop.php?id={$titles[$key]['id']}'></a></div></div>",
                'balloonContentFooter' =>  "<div class=ballon_footer>{$type_fish}</div>"
            ]
        ];
        $data['features'][] = $features;
    }
    return $data;
};

function hash_json(){
    $for_hash = '';
    foreach ($_GET as $k => $g){
        if(strpos($k,'_sfm_') !== false){
            $for_hash .= $g;
        }
    }
    if(isset($_GET['_sft_post_tag'])){
        $for_hash .= $_GET['_sft_post_tag'];
    }
    $hash = md5($for_hash);
    return $hash;
};
$dir = $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/fishing-report-yandex-map/json/";
$hash = hash_json();
$file_path = $dir . $hash . ".json";

ob_start('ob_gzhandler');
header('Content-Type: application/json; charset=utf-8');
if(!file_exists($file_path)){
    $data = create_json();
    file_put_contents ($file_path, json_encode($data));
    readfile($file_path);
}elseif((time() - 24*3600) > filemtime($file_path)){
    $data = create_json();
    file_put_contents ($file_path, json_encode($data));
    readfile($file_path);
}else{
    readfile($file_path);
}