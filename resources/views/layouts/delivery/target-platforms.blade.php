<select name="platform" id="platform"
        class="shadow form-control selectpicker show-tick"
        data-style="btn-secondary" data-live-search="false" data-dropup-auto="false" data-size="10"
        title="Select platform..." required
        wire:model.defer="platform">

    <option data-icon="fa fa-apple" value="Appstore">App Store</option>
    <option data-icon="fa fa-google" value="GooglePlay">Google Play</option>
</select>
