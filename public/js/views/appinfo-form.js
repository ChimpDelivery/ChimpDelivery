// get selected app data
$('select[name=app_name]').change(function () {
    let selectedOption = $('option:selected', this);
    let appStoreId = selectedOption.attr('data-appstore-id');
    let appStoreBundle = selectedOption.attr('data-appstore-bundle');

    updateAppstoreFields(appStoreId, appStoreBundle);
});

function updateAppstoreFields(appStoreId, appStoreBundle)
{
    let appstoreIdField = document.getElementById('appstore_id');
    appstoreIdField.value = appStoreId;

    let appstoreBundleField = document.getElementById('app_bundle');
    appstoreBundleField.value = appStoreBundle;
}

function preview()
{
    document.getElementById('app_icon_preview').src = URL.createObjectURL(event.target.files[0]);
    document.getElementById('app_icon_preview').hidden = false
}
