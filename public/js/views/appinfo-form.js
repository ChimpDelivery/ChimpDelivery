$('select[name=app_name]').change(function () {
    let selectedOption = $('option:selected', this);
    document.getElementById('appstore_id').value = selectedOption.attr('data-appstore-id');
    document.getElementById('app_bundle').value = selectedOption.attr('data-appstore-bundle');
});

function preview()
{
    let iconPreview = document.getElementById('app_icon_preview');
    iconPreview.src = URL.createObjectURL(event.target.files[0]);
    iconPreview.hidden = false
}
