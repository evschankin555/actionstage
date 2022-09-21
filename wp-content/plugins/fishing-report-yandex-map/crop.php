<?php
ini_set("display_errors", 1);

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
global $wpdb;
$id = intval($_GET['id']);
$sql_query = "SELECT post_content from $wpdb->posts WHERE id = {$id}";
$posts =  $wpdb->get_results($sql_query);
$image_orig_path = '';
$d = $posts[0]->post_content;
if(strpos($d,'<img src="') !== false){//если есть ещё картинки для парсинга, парсим ещё
    $img = explode('<img src="', $d);
    $img2 = explode('"', $img[1]);
    $image_orig_path = $_SERVER['DOCUMENT_ROOT'].trim($img2[0]);

}

$image = imagecreatefromjpeg($image_orig_path);
$dir = $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/fishing-report-yandex-map/img/";
$file_path = $dir . $id . ".webp";
$thumb_width = 390;
$thumb_height = 220;
$width = imagesx($image);
$height = imagesy($image);
$original_aspect = $width / $height;
$thumb_aspect = $thumb_width / $thumb_height;
if ( $original_aspect >= $thumb_aspect ){
    $new_height = $thumb_height;
    $new_width = $width / ($height / $thumb_height);
}else{
    $new_width = $thumb_width;
    $new_height = $height / ($width / $thumb_width);
}
$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
imagecopyresampled($thumb,
    $image,
    0 - ($new_width - $thumb_width) / 2,
    0 - ($new_height - $thumb_height) / 2,
    0, 0,
    $new_width, $new_height,
    $width, $height);
imagewebp($thumb, $file_path, 80);
header('Content-Type: image/webp');
readfile($file_path);


