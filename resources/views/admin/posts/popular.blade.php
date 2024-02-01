@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('link')
    <link href="{!! asset('admin') !!}/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/assets/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/assets/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/assets/css/colors.min.css" rel="stylesheet" type="text/css">
@endpush

@push('script')
    <script src="{!! asset('admin') !!}/global_assets/js/plugins/loaders/blockui.min.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/plugins/ui/ripple.min.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/plugins/visualization/d3/d3.min.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/plugins/ui/moment/moment.min.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/plugins/pickers/daterangepicker.js"></script>
    <script src="{!! asset('admin') !!}/assets/js/app.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_pages/dashboard.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/streamgraph.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/sparklines.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/lines.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/areas.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/donuts.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/bars.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/progress.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/heatmaps.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/pies.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/demo_charts/pages/dashboard/light/bullets.js"></script>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> -
                    Dashboard</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <div class="content pt-0">
        @if(session('success'))
            <div class="alert alert-success">
                {!! session()->get('success') !!}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {!! session()->get('error') !!}
            </div>
        @endif
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">Traffic sources</h6>
                    </div>
                    <div class="card-body py-0">
                        <div class="row">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Owner</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Comment count</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{!! $post->id !!}</td>
                                        <td>{!! $post->owner->name !!}</td>
                                        <td>{!! mb_substr($post->title, 0, 50) !!}</td>
                                        <td>{!! mb_substr($post->content, 0, 100) !!}</td>
                                        <td class="d-flex">{!! $post->comment_count !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8">
            </div>
            <div class="col-xl-4">
            </div>
        </div>
    </div>
@endsection
