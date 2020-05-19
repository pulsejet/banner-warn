/*
 * banner_warn plugin
 * @author pulsejet
 */

var banner_warn = {
    insertrow: function(evt) {
        let border = "none";
        let lineHeight = "32px";
        if (rcmail.env.banner_avatar[evt.uid].warn) {
            border = "2px solid red";
            lineHeight = "28px";
        }

        $('td.subject', evt.row.obj).before(`
            <td style="width: 42px; padding-left: 7px; padding-right: 3px; margin: 0px">
                <div style="background-color: #${rcmail.env.banner_avatar[evt.uid].color}; border-radius: 50%; width: 32px; height: 32px; color: white; text-align: center; vertical-align: middle; line-height: ${lineHeight}; font-size: 1.2em; padding:0 margin: 0; border: ${border}; box-sizing: border-box;">
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
