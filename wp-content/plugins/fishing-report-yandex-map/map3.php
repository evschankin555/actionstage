<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-Type: text/html; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

    global $wpdb;
    $and = '';
    foreach ($_GET as $k => $g){
        if(strpos($k,'_sfm_') !== false){
            $param = explode('_sfm_', $k);
            $p = $param[1];
            $v = $g;
            $and .= "AND $wpdb->postmeta.post_id in(SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '{$p}' AND meta_value LIKE '%{$v}%') ";
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
    }
    $sql_query = "SELECT $wpdb->posts.id, $wpdb->posts.post_content 
    from $wpdb->postmeta, $wpdb->posts WHERE  
    $wpdb->postmeta.post_id = $wpdb->posts.id 
    {$and}
    AND $wpdb->posts.post_status = 'publish'
    AND $wpdb->posts.post_type='post' GROUP BY $wpdb->posts.id";
    $posts =  $wpdb->get_results($sql_query);

//$wpdb = new wpdb('msmaxitf_wp4', '1u4&LaD^$', 'msmaxitf_wp4', 'localhost');

$j_coor = [];
foreach ($posts as $i => $p){
    $d = $p->post_content;
    $f = explode('center="', $d);
    $f2 = explode('"', $f[1]);
    $f3 = explode(',', $f2[0]);
    $lat = trim($f3[0]);
    $lon = trim($f3[1]);

    $j_coor[] = [$lat, $lon];
    //echo $i.'. ID:'.$p->id.' lat: '.$lat.' lon: '.$lon.' <br />';
}
$dates = [];
$dates['type'] = 'FeatureCollection';
$dates['features'] = [];
foreach ($j_coor as $item) {
    $dates['features'][] = Array('type' => 'FeatureCollection', 'features' => [
            ['type' => 'Feature', 'geometry' => Array('type' => 'Point', 'coordinates' => $item),
                'properties' => Array('name' => 'point'),
                'options' => Array('preset' => 'islands#blueDotIcon')]
    ]);
}


?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Добавление метки с собственным изображением</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=7f8e46d5-7865-40e2-89c2-832132a7f533" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(function () {
            var coorJSON = '<?php echo json_encode($dates); ?>';
            var coor = JSON.parse(coorJSON);
            points = coor;

            var customItemContentLayout = ymaps.templateLayoutFactory.createClass(
                '<h2 class=ballon_header>{{ properties.balloonContentHeader|raw }}</h2>' +
                '<div class=ballon_body>{{ properties.balloonContentBody|raw }}</div>' +
                '<div class=ballon_footer>{{ properties.balloonContentFooter|raw }}</div>'
            );
            var myMap = new ymaps.Map('map', {
                    center: [55.751574, 37.573856],
                    zoom: 9,
                    behaviors: ['default', 'scrollZoom']
                }, {
                    searchControlProvider: 'yandex#search'
                }),

                clusterer = new ymaps.Clusterer({
                    clusterize: true,
                    preset: 'islands#invertedBlueClusterIcons',
                    clusterHasBalloon: true,
                    groupByCoordinates: false,
                    clusterDisableClickZoom: true,
                    geoObjectOpenBalloonOnClick: true,
                    gridSize: 64,
                    clusterDisableClickZoom: true,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: true,
                    clusterBalloonContentLayout: 'cluster#balloonCarousel',
                    clusterBalloonItemContentLayout: customItemContentLayout,
                    clusterBalloonPanelMaxMapArea: 0,
                    clusterBalloonContentLayoutWidth: 400,
                    clusterBalloonContentLayoutHeight: 300,
                    clusterBalloonPagerSize: 10
                }),
                getPointData = function (index) {
                    return {
                        balloonContentHeader: window.titles[index].title,
                        balloonContentBody: '<div style="width: 390px;height: 220px;overflow: hidden;text-align: center;">'+
                            '<a href="'+window.titles[index].href+'" target="_blank"><img src="'+window.images[index]+'" style="max-width: 390px;max-height: 220px"></a></div>',
                        balloonContentFooter: (window.titles[index].type_fish != '') ? 'Тип рыбы на фото (предполагаемый):'+window.titles[index].type_fish : '' ,
                    };
                },
                getPointDataAjax = function (index) {
                    return {
                        balloonContentHeader: window.titles[index].title,
                        balloonContentBody: '<div style="width: 390px;height: 220px;overflow: hidden;text-align: center;">'+
                            '<a href="'+window.titles[index].href+'" target="_blank"><img src="'+window.images[index]+'" style="max-width: 390px;max-height: 220px"></a></div>',
                        balloonContentFooter: (window.titles[index].type_fish != '') ? 'Тип рыбы на фото (предполагаемый):'+window.titles[index].type_fish : '' ,
                    };
                },
                getPointOptions = function () {
                    return {
                        preset: 'islands#blueDotIcon',
                        balloonPanelMaxMapArea: 0,
                        openEmptyBalloon: true
                    };
                },
                geoObjects = [];

            /*for(var i = 0, len = points.length; i < len; i++) {
                geoObjects[i] = new ymaps.Placemark(points[i], {}, getPointOptions());
                geoObjects[i].events.add('balloonopen', function (e) {
                    placemark.properties.set('balloonContent', "...");
                    placemark.properties.set('balloonContentHeader', 'window.titles[index].title');
                    placemark.properties.set('balloonContentBody', '<div style="width: 390px;height: 220px;overflow: hidden;text-align: center;">');//+
                    //'<a href="'+window.titles[index].href+'" target="_blank"><img src="'+window.images[index]+'" style="max-width: 390px;max-height: 220px"></a></div>');
       placemark.properties.set('balloonContentFooter', '');
                    //balloonContentFooter: (window.titles[index].type_fish != '') ? 'Тип рыбы на фото (предполагаемый):'+window.titles[index].type_fish : '' ,
                });
            }*/
            var result = ymaps.geoQuery(points);
            myMap.geoObjects.add(result.search('geometry.type == "Point"').clusterize());

           /* clusterer.options.set({
                gridSize: 80,
                clusterDisableClickZoom: false
            });
            clusterer.add(geoObjects);
            myMap.geoObjects.add(clusterer);
            myMap.setBounds(clusterer.getBounds(), {
                checkZoomRange: true
            });*/
            var geolocation = ymaps.geolocation;
            geolocation.get({
                provider: 'browser',
                mapStateAutoApply: true
            }).then(function (result) {
                console.log(result.geoObjects.get(0).properties.get('boundedBy'));
                myMap.setBounds(result.geoObjects.get(0).properties.get('boundedBy'), {
                    checkZoomRange: true
                });
            });



            myMap.options.set('maxZoom', '15');
            //clusterer.balloon.open(clusterer.getClusters()[0]);
        });
    </script>

 <style>
            html, body, #map {
                width: 100%; height: 100%; padding: 0; margin: 0;
            }
            .ballon_header{
                font-size: 12px;
                text-align: center;
            }
            .ballon_footer{
                margin-top: 8px;
                text-align: center;
            }
</style>
    </head>
    <body>
    <div id="map" style="height: 400px;width: width: 100%"></div>
    <iframe  src="https://denpotok.ga/yandex-page" style="display: none;"></iframe>

    </body>
</html>
<?php
?>