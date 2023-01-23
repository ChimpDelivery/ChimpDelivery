@extends('master')

@section('title', 'Apps')

@section('content')
    <div class="container py-2">
        @include('layouts.jenkins.build-modal')
        <div class="card shadow bg-dark">
            @include('layouts.dashboard.card-header', [
                'text' => 'Apps',
                'icon' => 'fa-database',
                'additional' => view('layouts.appinfo.new-app-button')
            ])
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-borderless table-hover">
                        <thead>
                            <tr class="shadow-sm text-white text-center">
                                <th style="width: 25%;" scope="col">App</th>
                                <th style="width: 25%;" scope="col">Status</th>
                                <th style="width: 25%;" scope="col">Build</th>
                                <th style="width: 25%;" scope="col">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('layouts.appinfo.app-info-list')
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">{{ $appInfos->links() }}</div>
                </div>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => '<b>Runner Limit: 3</b>' . "<b class='pull-right'>{$totalAppCount} apps</b>"
            ])
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/views/workspace-apps.js') }}"></script>
@endsection
