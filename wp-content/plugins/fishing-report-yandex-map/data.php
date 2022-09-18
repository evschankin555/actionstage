<?php
//$wpdb->posts.post_title

$titles = [];
$images = [];
/*

            var titlesJSON = '<?php echo json_encode($titles); ?>';
            var imagesJSON = '<?php echo json_encode($images); ?>';

            window.titles = JSON.parse(titlesJSON);
            window.images = JSON.parse(imagesJSON);
 * */
//парсим тип рыбы для нижней метки что у нас на фото (решил бобнусом добавить, если что удалю).
$t = explode('Тип рыбы на фото (предполагаемый):</strong>', $d);
$t2 = explode('</p>', $t[1]);
$type_fish = rtrim($t2[0], '.');

//парсим картинки.
if(strpos($d,'<img src="') !== false){//если есть ещё картинки для парсинга, парсим ещё
    $img = explode('<img src="', $d);
    if(count($img) >= 2){
        for($j = 1; $j < count($img); $j++){
            $img2 = explode('"', $img[$j]);
            $images[] = get_bloginfo('wpurl').trim($img2[0]);
            $d = $img[1];
            $titles[] = ['title' => $p->post_title, 'type_fish' => $type_fish, 'href' => get_bloginfo('wpurl').'/'.$p->id.'/'];
        }
    }
}