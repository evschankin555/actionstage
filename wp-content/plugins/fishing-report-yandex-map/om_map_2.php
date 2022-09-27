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
                objectManager.objects.events.add('balloonopen', function (e) {
                    // Получим объект, на котором открылся балун.
                    var id = e.get('objectId'),
                        geoObject = objectManager.objects.getById(id);
                    // Загрузим данные для объекта при необходимости.
                    downloadContent([geoObject], id);
                });

                objectManager.clusters.events.add('balloonopen', function (e) {
                    // Получим id кластера, на котором открылся балун.
                    var id = e.get('objectId'),
                        // Получим геообъекты внутри кластера.
                        cluster = objectManager.clusters.getById(id),
                        geoObjects = cluster.properties.geoObjects;

                    // Загрузим данные для объектов при необходимости.
                    downloadContent(geoObjects, id, true);
                });

                function downloadContent(geoObjects, id, isCluster) {
                    // Создадим массив меток, для которых данные ещё не загружены.
                    var array = geoObjects.filter(function (geoObject) {
                            return geoObject.properties.balloonContent === 'идет загрузка...' ||
                                geoObject.properties.balloonContent === 'Not found';
                        }),
                        // Формируем массив идентификаторов, который будет передан серверу.
                        ids = array.map(function (geoObject) {
                            return geoObject.id;
                        });
                    if (ids.length) {
                        // Запрос к серверу.
                        // Сервер обработает массив идентификаторов и на его основе
                        // вернет JSON-объект, содержащий текст балуна для
                        // заданных меток.
                        ymaps.vow.resolve($.ajax({
                            // Обратите внимание, что серверную часть необходимо реализовать самостоятельно.
                            //contentType: 'application/json',
                            //type: 'POST',
                            //data: JSON.stringify(ids),
                            url: 'content.json',
                            dataType: 'json',
                            processData: false
                        })).then(function (data) {
                            // Имитируем задержку от сервера.
                            return ymaps.vow.delay(data, 1000);
                        }).then(
                            function (data) {
                                geoObjects.forEach(function (geoObject) {
                                    // Содержимое балуна берем из данных, полученных от сервера.
                                    // Сервер возвращает массив объектов вида:
                                    // [ {"balloonContent": "Содержимое балуна"}, ...]
                                    geoObject.properties.balloonContent = data[geoObject.id].balloonContent;
                                });
                                // Оповещаем балун, что нужно применить новые данные.
                                setNewData();
                            }, function () {
                                geoObjects.forEach(function (geoObject) {
                                    geoObject.properties.balloonContent = 'Not found';
                                });
                                // Оповещаем балун, что нужно применить новые данные.
                                setNewData();
                            }
                        );
                    }

                    function setNewData(){
                        if (isCluster && objectManager.clusters.balloon.isOpen(id)) {
                            objectManager.clusters.balloon.setData(objectManager.clusters.balloon.getData());
                        } else if (objectManager.objects.balloon.isOpen(id)) {
                            objectManager.objects.balloon.setData(objectManager.objects.balloon.getData());
                        }
                    }
                }

            jQuery.ajax({
                url: "https://fishing-report.ru/wp-content/plugins/fishing-report-yandex-map/om_data_2.php"
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
    <div id="map" style="height: 900px;width: width: 100%"></div>
    </body>
    </html>
<?php
?>