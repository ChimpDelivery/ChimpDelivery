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

    // initialize clipboard
    let clipboard = new ClipboardJS('.btn-copy');
    clipboard.on('success', function(e) {
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
    });
})
