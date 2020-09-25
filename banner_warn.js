/*
 * banner_warn plugin
 * @author pulsejet
 */

var banner_warn = {
    insertrow: function(evt) {
        // Check if we have the required data
        if (!rcmail.env.banner_avatar || !rcmail.env.banner_avatar[evt.uid]) return;

        // Get object
        const obj = rcmail.env.banner_avatar[evt.uid];

        // Border for warning the user
        const warn = obj.warn ? 'warn ' : '';
        const calert = obj.alert ? 'alert ' : '';

        // Get image avatar
        const showImages = rcmail.env.banner_avatar_images;
        const image = (warn || calert || !showImages) ? '' : './?_task=addressbook&_action=photo&_email=' + obj.from + '&_error=1';

        // Add column of avatar
        $('td.subject', evt.row.obj).before(
            $('<td/>', { class: 'banner-warn' }).append(
                $('<div />', { class: 'avatar ' + warn + calert }).append(
                    $('<img />', { src: image, alt: '' }).on('error', function () {
                        $(this).replaceWith($('<span />').html(obj.name));
                    }).on('load', function () {
                        $(this).css('visibility', 'visible');
                    }).css('visibility', 'hidden')
                ).append(
                    $('<span />', { class: 'tick' }).html('&#10003;')
                ).css('color', '#' + obj.color)
            ).on('mousedown', function (event) {
                rcmail.message_list.select_row(evt.uid, CONTROL_KEY, true);
                event.stopPropagation();
            }).on('touchstart', function (event) {
                event.stopPropagation();
            })
        );

        // Add column of avatar if does not exit
        if ($('th.banner-warn').length === 0 && $('th.subject').length > 0) {
            $('th.subject').before(
                $('<th/>', { class: 'banner-warn' })
            );
        }
    }
};

window.rcmail && rcmail.addEventListener('init', function(evt) {
        if (rcmail.gui_objects.messagelist) {
            rcmail.addEventListener('insertrow', banner_warn.insertrow);

            const _hrow = rcmail.message_list.highlight_row.bind(rcmail.message_list);
            rcmail.message_list.highlight_row = function(...args) {
                if (args[1]) {
                    $(rcmail.message_list.tbody).addClass('multiselect');
                } else {
                    $(rcmail.message_list.tbody).removeClass('multiselect');
                }
                _hrow(...args);
            }
        }
});
