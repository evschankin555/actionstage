<?php
/*
Plugin Name: Яндекс карты для fishing-report.ru
Plugin URI: #
Description: Данный плагин помогает искать отчёты о рыбалке на яндекс картах
Version: 1.0.0
Author: FastProWeb
Author URI: https://kwork.ru/user/fastproweb

Copyright 2022  #)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA


*/
add_shortcode('fishing_report_map', function () {
    $d = '?';
    if(!empty($_GET)){
        $d .= http_build_query($_GET);
    }
     ?>
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
    <div style="position: relative;margin-top: 20px;"><button id="btn_map" href="#map" style="
    display: block;margin-right: 0.5em;border: 2px solid transparent;min-width: 2.5em;text-align: center;text-decoration: none;
    border-radius: 0.25rem; color: inherit;border-color: var(--global-palette-btn-bg);background: var(--global-palette-btn-bg);   color: var(--global-palette-btn);
    padding: 0 15px;position: absolute;top: -35px;   right: 16px;font-size: 14px;">Скрыть карту</button>
        <div id="map" style="height: 400px;width: width: 100%"></div>
    </div>
    <script type="text/javascript">
        (function (factory) {
            if (typeof define === 'function' && define.amd) {
                // AMD (Register as an anonymous module)
                define(['jquery'], factory);
            } else if (typeof exports === 'object') {
                // Node/CommonJS
                module.exports = factory(require('jquery'));
            } else {
                // Browser globals
                factory(jQuery);
            }
        }(function ($) {
            var pluses = /\+/g;
            function encode(s) {
                return config.raw ? s : encodeURIComponent(s);
            }
            function decode(s) {
                return config.raw ? s : decodeURIComponent(s);
            }
            function stringifyCookieValue(value) {
                return encode(config.json ? JSON.stringify(value) : String(value));
            }
            function parseCookieValue(s) {
                if (s.indexOf('"') === 0) {
                    // This is a quoted cookie as according to RFC2068, unescape...
                    s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
                }

                try {
                    // Replace server-side written pluses with spaces.
                    // If we can't decode the cookie, ignore it, it's unusable.
                    // If we can't parse the cookie, ignore it, it's unusable.
                    s = decodeURIComponent(s.replace(pluses, ' '));
                    return config.json ? JSON.parse(s) : s;
                } catch(e) {}
            }
            function read(s, converter) {
                var value = config.raw ? s : parseCookieValue(s);
                return $.isFunction(converter) ? converter(value) : value;
            }
            var config = $.cookie = function (key, value, options) {

                // Write

                if (arguments.length > 1 && !$.isFunction(value)) {
                    options = $.extend({}, config.defaults, options);

                    if (typeof options.expires === 'number') {
                        var days = options.expires, t = options.expires = new Date();
                        t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
                    }

                    return (document.cookie = [
                        encode(key), '=', stringifyCookieValue(value),
                        options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                        options.path    ? '; path=' + options.path : '',
                        options.domain  ? '; domain=' + options.domain : '',
                        options.secure  ? '; secure' : ''
                    ].join(''));
                }

                // Read

                var result = key ? undefined : {},
                    // To prevent the for loop in the first place assign an empty array
                    // in case there are no cookies at all. Also prevents odd result when
                    // calling $.cookie().
                    cookies = document.cookie ? document.cookie.split('; ') : [],
                    i = 0,
                    l = cookies.length;

                for (; i < l; i++) {
                    var parts = cookies[i].split('='),
                        name = decode(parts.shift()),
                        cookie = parts.join('=');

                    if (key === name) {
                        // If second argument (value) is a function it's a converter...
                        result = read(cookie, value);
                        break;
                    }

                    // Prevent storing a cookie that we couldn't decode.
                    if (!key && (cookie = read(cookie)) !== undefined) {
                        result[name] = cookie;
                    }
                }

                return result;
            };
            config.defaults = {};
            $.removeCookie = function (key, options) {
                // Must not alter options, thus extending a fresh object...
                $.cookie(key, '', $.extend({}, options, { expires: -1 }));
                return !$.cookie(key);
            };
        }));
        function show_map(){
            jQuery('#map').css('height', '400px');
            jQuery('#btn_map').text('Скрыть карту');
            jQuery.cookie('btn_map', 'show');
        };
        function hide_map(){
            jQuery('#map').css('height', '0px');
            jQuery('#btn_map').text('Раскрыть карту');
            jQuery.cookie('btn_map', 'hide');
        };
        if(jQuery.cookie('btn_map') == 'show'){
            show_map();
        }else{
            hide_map();
        }
        jQuery('#btn_map').click(function (){
           if(jQuery(this).text() == 'Скрыть карту'){
               hide_map();
           }else{
               show_map();
           }
        });
        var oldURL = "";
        var currentURL = window.location.href;
        window.isFirstLoad = true;
        function checkURLchange(currentURL){
            if(currentURL != oldURL || window.isFirstLoad){
                if(typeof currentURL != "undefined" || window.isFirstLoad){
                    window.isFirstLoad = false;
                    var $param = currentURL.split('?');
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
                        window.objectManager.removeAll();
                        window.objectManager.add(data);
                        window.myMap.setBounds(window.objectManager.getBounds());
                        var geolocation = ymaps.geolocation;
                        geolocation.get({
                            provider: 'browser',
                            mapStateAutoApply: true
                        }).then(function (result) {
                            window.myMap.setBounds(result.geoObjects.get(0).properties.get('boundedBy'), {
                                checkZoomRange: true
                            });
                        });
                    });
                }
                oldURL = currentURL;
            }
            oldURL = window.location.href;
        }
        function createMap(){

            ymaps.ready(function () {
                window.myMap = new ymaps.Map('map', {
                        center: [55.76, 37.64],
                        zoom: 10
                    }, {
                        searchControlProvider: 'yandex#search'
                    }),
                    window.objectManager = new ymaps.ObjectManager({
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
                window.objectManager.objects.options.set('preset', 'islands#greenDotIcon');
                window.objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
                window.myMap.geoObjects.add(window.objectManager);

                setInterval(function() {
                    checkURLchange(window.location.href);
                }, 1000);
            });
        };
        jQuery(document).ready(function(){
            createMap();
        });

    </script>
    <?php

});