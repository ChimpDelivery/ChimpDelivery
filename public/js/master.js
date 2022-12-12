$(document).ready(function ()
{
    // init popover
    $('[data-toggle="popover"]').popover()
    $('.popover-dismiss').popover({ trigger: 'focus' })

    // init bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip()

    // init toasts
    $('#toast-flash').toast('show');
    if (!getCookie('daily-toast-cookie'))
    {
        $('#toast-talus').toast('show');
    }
})
