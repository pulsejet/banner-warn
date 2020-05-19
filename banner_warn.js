/*
 * authres_status plugin
 * @author pimlie
 */

var banner_warn = {
    insertrow: function(evt) {
        $('td.subject', evt.row.obj).before(`
            <td style="width: 42px; padding-left: 7px; padding-right: 3px; margin: 0px">
                <div style="background-color: #${rcmail.env.banner_avatar[evt.uid].color}; border-radius: 50%; width: 32px; height: 32px; color: white; text-align: center; vertical-align: middle; line-height: 32px; font-size: 1.2em; padding:0 margin: 0;">
                    ${rcmail.env.banner_avatar[evt.uid].name}
                </div>
            </td>`
        );
    }
};

window.rcmail && rcmail.addEventListener('init', function(evt) {
        if (rcmail.gui_objects.messagelist) {
            rcmail.addEventListener('insertrow', banner_warn.insertrow);
        }
});
