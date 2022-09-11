(function ($) {
    if ($('.tlp-color').length && $.fn.wpColorPicker) {
        $('.tlp-color').wpColorPicker();
    }
    if ($("#tlp_team_sc_settings_meta .tlp-color").length && $.fn.wpColorPicker) {
        var cOptions = {
            defaultColor: false,
            change: function (event, ui) {
                renderTlpTeamPreview();
            },
            clear: function () {
                renderTlpTeamPreview();
            },
            hide: true,
            palettes: true
        };
        $("#tlp_team_sc_settings_meta .tlp-color").wpColorPicker(cOptions);
    }
    //   Depricated .............
    imageSize();

    $("#rt-feature-img-size").on('change', function () {
        imageSize();
    });
    $("#sc-layout").on('change', function () {
        var val = $(this).val();
        if (val == 'carousel') {
            $("#carousel-sc").slideDown();
        } else {
            $("#carousel-sc").slideUp();
        }
    });

    //  End Depricated ...

    $("#tlp_team_sc_settings_meta").on('change', 'select,input', function () {
        renderTlpTeamPreview();
    });
    $("#tlp_team_sc_settings_meta").on("input propertychange", function () {
        renderTlpTeamPreview();
    });


    function featureImageEffect() {
        if ($("#ttp_image").is(':checked')) {
            $(".tlp-field-holder.ttp-feature-image-option").hide();
        } else {
            $(".tlp-field-holder.ttp-feature-image-option").show();
        }
    }

    function imageSize() {
        var size = $("#rt-feature-img-size").val();
        if (size == "ttp_custom") {
            $(".rt-custom-image-size-wrap").show();
        } else {
            $(".rt-custom-image-size-wrap").hide();
        }
    }

    function imageSizeEffect() {
        var size = $("#ttp_image_size").val();
        if (size == "ttp_custom") {
            $("#ttp_custom_image_size_holder").show();
        } else {
            $("#ttp_custom_image_size_holder").hide();
        }
    }

    function paginationEffect() {
        if ($("#ttp_pagination").is(':checked')) {
            $(".tlp-field-holder.tlp-pagination-item").show();
        } else {
            $(".tlp-field-holder.tlp-pagination-item").not('.pagination').hide();
        }
    }

    function useEffect() {
        featureImageEffect();
        var layout = $("#layout").val();
        if (layout) {
            var isGrid = layout.match(/^layout/i),
                isCarousel = layout.match(/^carousel/i),
                isIsotope = layout.match(/^isotope/i);
            if (isGrid) {
                $(".tlp-field-holder.tlp-carousel-item").hide();
                $(".tlp-field-holder.tlp-pagination-item.pagination").show();
            } else if (isCarousel) {
                $(".tlp-field-holder.tlp-pagination-item").hide();
                $(".tlp-field-holder.tlp-carousel-item").show();
            } else if (isIsotope) {
                $(".tlp-field-holder.tlp-carousel-item").hide();
                $(".tlp-field-holder.tlp-pagination-item.pagination").show();
            }
        }

        if ($(".tlp-field-holder.tlp-pagination-item.pagination").is(':visible')) {
            paginationEffect();
        }
        imageSizeEffect();
    }

    renderTlpTeamPreview();
    useEffect();
    $("#layout").on('change', function () {
        useEffect();
    });
    $("#ttp_image").on('change', function () {
        featureImageEffect();
    });
    $("#ttp_image_size").on('change', function () {
        imageSizeEffect();
    });
    $("#ttp_pagination").on('change', function () {
        paginationEffect();
    });

    $(".rt-tab-nav li").on('click', 'a', function (e) {
        e.preventDefault();
        var container = $(this).parents('.rt-tab-container'),
            nav = container.children('.rt-tab-nav'),
            content = container.children(".rt-tab-content"),
            $this = $(this),
            $id = $this.attr('href');
        content.hide();
        nav.find('li').removeClass('active');
        $this.parent().addClass('active');
        container.find($id).show();
    });

    function renderTlpTeamPreview() {
        var target = $('#tlp_team_sc_settings_meta');
        if (target.length) {
            var data = target.find('input[name],select[name],textarea[name]').serialize();
            // Add Shortcode ID
            data = data + '&' + $.param({'sc_id': $('#post_ID').val() || 0});
            TlpTeamPreviewAjaxCall(null, 'tlpTeamPreviewAjaxCall', data, function (res) {
                if (!data.error) {
                    $("#tlp-team-preview-container").html(res.data);
                    initTlpTeam();
                }
            });
        }
    }

    function TlpTeamPreviewAjaxCall(element, action, arg, handle) {
        var data;
        if (action) data = "action=" + action;
        if (arg) data = arg + "&action=" + action;
        if (arg && !action) data = arg;

        var n = data.search(ttp.nonceID);
        if (n < 0) {
            data = data + "&" + ttp.nonceID + "=" + ttp.nonce;
        }
        $.ajax({
            type: "post",
            url: ttp.ajaxurl,
            data: data,
            beforeSend: function () {
                $('#tlp-team-preview-container').addClass('loading');
                $('#tlp-team-response .spinner').addClass('is-active');
            },
            success: function (data) {
                $('#tlp-team-preview-container').removeClass('loading');
                $('#tlp-team-response .spinner').removeClass('is-active');
                handle(data);
            }
        });
    }


    function AjaxCall(element, action, arg, handle) {
        var data, n;
        if (action) data = "action=" + action;
        if (arg) data = arg + "&action=" + action;
        if (arg && !action) data = arg;
        n = data.search("tlp_nonce");
        if (n < 0) {
            data = data + "&tlp_nonce=" + tlp_var.tlp_nonce;
        }

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            beforeSend: function () {
                $("<span class='tlp_loading'></span>").insertAfter(element);
            },
            success: function (data) {
                $(".tlp_loading").remove();
                handle(data);
            }
        });
    }

    $("#tlp-team-settings").on('submit', function (e) {
        e.preventDefault();

        var response = $('#response').hide(),
            arg = $(this).serialize(),
            bindElement = $('#tlpSaveButton');
        AjaxCall(bindElement, 'tlpTeamSettings', arg, function (data) {
            if (data.error) {
                response
                    .addClass('error')
                    .removeClass('success');
            } else {
                response
                    .removeClass('error')
                    .addClass('success');
            }
            response.text(data.msg).show();
        });

        return false;
    })

})(jQuery);

