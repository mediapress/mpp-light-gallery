jQuery(document).ready(function ($) {

    $(document).on('click', '.mpp-gallery-photo .mpp-gallery-cover', function () {

        var $this = $(this);
        var gallery_id = $(this).data('mpp-gallery-id');

        build_light_gallery(gallery_id, $this);

        return false;
    });

    $(document).on('click', '.mpp-light-gallery-activity a', function () {

        var $this = $(this),
            activity_id = $this.parent().attr('data-activity-id');

        build_light_on_activity(activity_id, $this);

        return false;
    });

    $('.mpp-light-gallery img').click(function (e) {

        var $this = $(this),
            gallery_id = $this.attr('data-gallery-id');

        build_light_gallery(gallery_id, $this);

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
});
