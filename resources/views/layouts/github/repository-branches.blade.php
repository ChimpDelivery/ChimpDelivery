<div wire:init="FetchBranches">
    @if($this->branches)
        <select name="branch"
                id="branch"
                class="shadow form-control selectpicker show-tick"
                data-style="btn-secondary"
                data-live-search="false"
                data-dropup-auto="false"
                data-size="10"
                title="Select branch..."
                required
                wire:model.defer="branch"
        >

            @foreach($this->branches as $repoBranch)
                <option data-icon="fa fa-github" value="{{ $repoBranch->name }}">
                    {{ $repoBranch->name }}
                </option>
            @endforeach
        </select>
    @else
        Branches Loading...
    @endif
</div>
