<?php get_template_part('framework/partials/footer-partials/footer-sidebars'); ?>
<?php get_template_part('framework/partials/footer-partials/copyright'); ?>

<?php wp_footer(); ?>

<?php if(is_front_page() && is_page_template('page-templates/directory-home-widgets.php')): ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var map = new GMaps({
                div: '.front-map',
                lat: '40.712784',
                lng: '-74.005941',
                scrollwheel: false,
                zoom:5
            });

            var styles = [
                {
                    "featureType": "water",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#b5cbe4"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "stylers": [
                        {
                            "color": "#efefef"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#83a5b0"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#bdcdd3"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#e3eed3"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "lightness": 33
                        }
                    ]
                },
                {
                    "featureType": "road"
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {},
                {
                    "featureType": "road",
                    "stylers": [
                        {
                            "lightness": 20
                        }
                    ]
                }
            ];

            map.addStyle({ styledMapName: "Styled Map",
                styles: styles,
                mapTypeId: "map_style"});

            map.setStyle("map_style");

            jQuery('.single-hidden-listing').each(function(){


                var listing_name = jQuery(this).attr('data-name');
                var listing_address = jQuery(this).attr('data-address');

                GMaps.geocode({
                    address: listing_address,
                    callback: function(results, status) {
                        if (status == 'OK') {
                            var latlng = results[0].geometry.location;
                            map.setCenter(latlng.lat(), latlng.lng());
                            map.addMarker({
                                lat: latlng.lat(),
                                lng: latlng.lng(),
                                infoWindow:{
                                    content: '<div class="info-window">' +
                                    '<h4 class="info-window-name">'+listing_name+
                                    '</h4> <h5 ' +
                                    'class="info-window-address"><span ' +
                                    'class="fa ' +
                                    'fa-map-marker"></span>'+listing_address+'</h5>' +
                                    ' </div>'
                                }
                            });
                        }
                    }
                });

            });
        });
    </script>
<?php endif; ?>
</body>
</html>