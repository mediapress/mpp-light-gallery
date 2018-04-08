jQuery(document).ready(function ($) {

    MPP_Light_Gallery.enable_on_cover          = MPP_Light_Gallery.enable_on_cover - 0;
    MPP_Light_Gallery.enable_in_activity       = MPP_Light_Gallery.enable_in_activity - 0;
    MPP_Light_Gallery.enable_on_single_gallery = MPP_Light_Gallery.enable_on_single_gallery - 0;

    if ( MPP_Light_Gallery.enable_on_cover ) {

        $(document).off('click', '.mpp-gallery a.mpp-gallery-cover');

        $(document).on('click', '.mpp-gallery-photo .mpp-gallery-cover', function () {

            var $this = $(this);
            var gallery_id = $(this).data('mppGalleryId');

            build_light_gallery(gallery_id, $this);

            return false;
        });
    }

    if ( MPP_Light_Gallery.enable_in_activity ) {

        $(document).off('click', '.mpp-activity-photo-list a');

        $(document).on('click', '.mpp-activity-photo-list a', function () {

            var $this = $(this), activity_id = $this.data('mppActivityId');

            build_light_on_activity( activity_id, $this );

            return false;
        });
    }

    if ( MPP_Light_Gallery.enable_on_single_gallery ) {

        $(document).off('click', '.mpp-single-gallery-photo-list img');

        $('.mpp-single-gallery-photo-list img').click(function () {

            var $this = $(this), gallery_id = $this.closest('div.mpp-single-gallery-photo-list').data('galleryId');

            build_light_gallery( gallery_id, $this );

            return false;
        });
    }

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
