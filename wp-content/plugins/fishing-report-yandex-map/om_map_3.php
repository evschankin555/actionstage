<?php

?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Добавление метки с собственным изображением</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="//fishing-report.ru/wp-includes/js/jquery/jquery.js?ver=1.12.4-wp" id="jquery-core-js"></script>
        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=7f8e46d5-7865-40e2-89c2-832132a7f533" type="text/javascript"></script>
        <script type="text/javascript">
            ymaps.ready(function () {
            var myMap = new ymaps.Map('map', {
                    center: [55.76, 37.64],
                    zoom: 10
                }, {
                    searchControlProvider: 'yandex#search'
                }),
                objectManager = new ymaps.ObjectManager({
                    // Чтобы метки начали кластеризоваться, выставляем опцию.
                    clusterize: true,
                    // ObjectManager принимает те же опции, что и кластеризатор.
                    gridSize: 32,
                    clusterBalloonContentLayout: 'cluster#balloonCarousel',
                    clusterBalloonPanelMaxMapArea: 0,
                    clusterBalloonContentLayoutWidth: 400,
                    clusterBalloonContentLayoutHeight: 300,
                    clusterBalloonPagerSize: 10,
                    clusterDisableClickZoom: true
                });

            // Чтобы задать опции одиночным объектам и кластерам,
            // обратимся к дочерним коллекциям ObjectManager.
            objectManager.objects.options.set('preset', 'islands#greenDotIcon');
            objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
            myMap.geoObjects.add(objectManager);
                var $param = document.location.href.split('?');
                var p = 'https://fishing-report.ru/wp-content/plugins/fishing-report-yandex-map/om_data_3.php?'+$param[1];
                if(document.location.href.indexOf('/tag/') !== -1){
                    var d = document.location.href.split("tag/");
                    if(d.length > 1){
                        var d2 = d[1].split('/');
                        p += '&tag=' + d2[0];
                    }
                }

            jQuery.ajax({
                url: p
            }).done(function(data) {
                objectManager.add(data);
                var geolocation = ymaps.geolocation;
                geolocation.get({
                    provider: 'browser',
                    mapStateAutoApply: true
                }).then(function (result) {
                    myMap.setBounds(result.geoObjects.get(0).properties.get('boundedBy'), {
                        checkZoomRange: true
                    });
                });
            });

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
            .ballon_body div{
                width: 390px;
                height: 220px;
                overflow: hidden;
                text-align: center;
            }
            .ballon_body img{
                max-width: 390px;
                max-height: 220px;
            }
        </style>
    </head>
    <body>
    <div id="map" style="height: 400px;width: width: 100%"></div>
    </body>
    </html>
<?php
?>