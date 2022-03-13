@extends('layouts.master')

@section('title', 'Create Bundle')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form name="add-add-info-form" id="create-bundle-form" method="post" action="{{url('dashboard/store-bundle')}}">
                @csrf
                <div class="form-group">
                    <label for="bundle_id">Bundle Identifier</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">com.Talus.</span>
                        </div>
                        <input type="text" class="form-control" name="bundle_id" aria-describedby="basic-addon3" required="">
                    </div>

                </div>
                <div class="form-group">
                    <label for="bundle_name">Identifier Name</label>
                    <input type="text" name="bundle_name" class="form-control" placeholder="identifier name..." required="">
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Create Bundle</button>
                <button type="reset" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
    </div>
</div>
@endsection