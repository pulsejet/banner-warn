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
        const warn = obj.warn ? "warn" : "";
        const calert = obj.alert ? "alert" : "";

        // Get image avatar
        const image = (warn || calert) ? "" : `./?_task=addressbook&_action=photo&_email=${obj.from}&_error=1`;

        // Add column of avatar
        $('td.subject', evt.row.obj).before(`
            <td class="banner-warn">
                <div class="avatar ${warn} ${calert}" style='color: #${obj.color};'>
                    <img src="${image}" style="color: white;" alt="${obj.name}" />
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
