jQuery(document).ready(function ($) {

    if ( MPP_Light_Gallery.enabled_on_cover ) {
        $(document).on('click', '.mpp-gallery-photo a.mpp-gallery-cover img', function (e) {
            e. preventDefault();

            var $this = $(this);
            var gallery_id = $(this).parent('a.mpp-gallery-cover').data('mppGalleryId');

            build_light_gallery(gallery_id, $this);

            return false;
        });
    }

    if ( 'light-gallery' == MPP_Light_Gallery.activity_default_view ) {
        $(document).on('click', '.mpp-activity-photo-list img', function (e) {
            e. preventDefault();

            var $this = $(this),
                activity_id = $this.data('mppActivityId');

            build_light_on_activity(activity_id, $this);

            return false;
        });
    }

    $('.mpp-light-gallery img').click(function () {

        var $this = $(this),
            gallery_id = $this.attr('data-gallery-id');

        build_light_gallery(gallery_id, $this);

        return false;
    });

    $('.mpp-light-gallery-shortcode img').click(function () {

        var $this = $(this),
            gallery_ids = $this.parents('.mpp-light-gallery-shortcode').data('galleryIds'),
            media_id = $this.parent().data('mppMediaId');

        build_shortcode_light_gallery(gallery_ids, media_id, $this);

        return false;
    });

    function build_light_gallery(gallery_id, $el) {

        var url = MPP_Light_Gallery.url;
        var media_id = ( $el.attr('data-media-id') != undefined )
            ? $el.attr('data-media-id')
            : 0;

        $.get(url, {
                action: "mpp_light_gallery_get_media",
                gallery_id: gallery_id
            },
            function (resp) {
                var index = 0;//by default first
                for (var i = 0; i < resp.length; i++) {
                    if (media_id == resp[i].id) {
                        index = i;
                        break;
                    }
                }

                $el.lightGallery({
                    download: false,
                    index: index,
                    dynamic: true,
                    dynamicEl: resp
                });
            }, 'json');
    }

    function build_light_on_activity(activity_id, $el) {

        var url = MPP_Light_Gallery.url;
        var position = $el.index();

        $.get(url, {
                action: "mpp_light_activity_get_media",
                activity_id: activity_id
            },
            function (resp) {
                $el.lightGallery({
                    download: false,
                    index: position,
                    dynamic: true,
                    dynamicEl: resp
                });
            }, 'json');
    }

    function build_shortcode_light_gallery(gallery_ids, clicked_media_id, $el) {
        var url = MPP_Light_Gallery.url;

        $.get(
            url,
            {
                action: "mpp_light_gallery_shortcode_get_media",
                galleries: gallery_ids
            },
            function (resp) {
                var index = 0;//by default first
                for (var i = 0; i < resp.length; i++) {
                    if (clicked_media_id == resp[i].id) {
                        index = i;
                        break;
                    }
                }

                $el.lightGallery({
                    download: false,
                    index: index,
                    dynamic: true,
                    dynamicEl: resp
                });
            },
            'json'
        );
    }
});
