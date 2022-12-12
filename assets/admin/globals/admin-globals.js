const homeUrl = ajax_object.home_url;
const ajaxUrl = ajax_object.ajax_url;

let connectionId

function sendNewMessage() {

    connectionId = jQuery(".trinsic-chat-select").val()
    let newMessage = jQuery(".trinsic-chat-textarea").val()

    if (confirm("Do you want to send this message?")) {

        let data = {
            'action': 'trinsicSendMessage', 'message': `${newMessage}`, 'connectionId': `${connectionId}`
        }
        jQuery.post(ajaxUrl, data, () => {
            jQuery(".trinsic-chat-textarea").val('');
            window.location.search += `&connectionId=${connectionId}`;
        })

    }
}

jQuery(document).ready(() => {
    connectionId = jQuery(".trinsic-chat-select").val()
    jQuery(".trinsic-chat-select").change(() => {
        jQuery("#trinsic-chat-form").submit()
    })
})
