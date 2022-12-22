$(document).ready(function ()
{
    // init popover
    $('[data-toggle="popover"]').popover();
    $('.popover-dismiss').popover({ trigger: 'focus' });

    // hide copied-text info
    $('[data-trigger="click"]').on('shown.bs.tooltip', function (event) {
        setTimeout(function() {
            $(event.target).tooltip('hide');
        }, 1500);
    });

    // init bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // init toasts
    $('#toast-flash').toast('show');
    if (!getCookie('daily-toast-cookie'))
    {
        $('#toast-talus').toast('show');
    }
})

function copyToClip(inputId)
{
    // Get the text field
    let copyText = document.getElementById(inputId);

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    if (!copyText.value) { return; }

    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);

    copyText.tooltip('show')
}
