jQuery(document).ready(function ($) {

    if ( MPP_Light_Gallery.enabled_on_cover ) {

        $(document).off('click', '.mpp-gallery a.mpp-gallery-cover');

        $(document).on('click', '.mpp-gallery-photo .mpp-gallery-cover', function () {

            var $this = $(this);
            var gallery_id = $(this).data('mppGalleryId');

            build_light_gallery(gallery_id, $this);

            return false;
        });
    }

    $(document).off('click', '.mpp-activity-photo-list a');

    $(document).on('click', '.mpp-activity-photo-list a', function () {

        var $this = $(this), activity_id = $this.data('mppActivityId');

        build_light_on_activity( activity_id, $this );

        return false;
    });

    $('.mpp-single-gallery-photo-list img').click(function () {

        var $this = $(this), gallery_id = $this.closest('div.mpp-single-gallery-photo-list').data('galleryId');

        build_light_gallery( gallery_id, $this );

        return false;
    });

    function build_light_gallery(gallery_id, $el) {

        var url = MPP_Light_Gallery.url;
        var media_id = ( $el.closest('a.mpp-photo-thumbnail').data('mppMediaId') != undefined )
            ? $el.closest('a.mpp-photo-thumbnail').data('mppMediaId')
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
