/*
 * banner_warn plugin
 * @author pulsejet
 */

var banner_warn = {
    insertrow: function(evt) {
        // Check if we have the required data
        if (!rcmail.env.banner_avatar || !rcmail.env.banner_avatar[evt.uid]) return;

        // Border for warning the user
        const warn = rcmail.env.banner_avatar[evt.uid].warn ? "warn" : "";

        // Add column of avatar
        $('td.subject', evt.row.obj).before(`
            <td class="banner-warn">
                <div style="color: #${rcmail.env.banner_avatar[evt.uid].color};" class="avatar ${warn}">
                    <span style="color: white"> ${rcmail.env.banner_avatar[evt.uid].name} </span>
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
