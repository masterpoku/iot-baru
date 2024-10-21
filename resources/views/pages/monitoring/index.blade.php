@push('css_vendor')
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
@endpush

@extends('layouts.app')
@section('title', 'Monitoring')
@section('breadcrumb')
    @parent
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
           
        </div>
        <div class="card-body">
            <table id="table-sensors" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                            <div class="custom-control custom-control-danger custom-checkbox">
                                <input type="checkbox" name="main_checkbox" class="custom-control-input" id="colorCheckq5"/>
                                <label class="custom-control-label" for="colorCheckq5"></label>
                            </div> 
                        </th>
                        <th>Tanggal</th>
                        <th>Rate</th>
                        <th>Volume</th>
                        <th>Temp</th>
                        <th>Humd</th>
                        <th>Weight</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('script')
    {!! Html::script('template/assets/js/asset.js') !!}
    <script type="text/javascript">
        var table = $('#table-sensors').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            rowGroup: {
                dataSrc: 2
            },
            ajax: "{{route('monitoring.index')}}",
            columns: [{
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'flow_rate',
                name: 'flow_rate'
            },
            {
                data: 'total_volume',
                name: 'total_volume'
            },
            {
                data: 'temperature',
                name: 'temperature'
            },
            {
                data: 'humidity',
                name: 'humidity'
            },
            {
                data: 'weight',
                name: 'weight'
            },
            {
                data: 'status_id',
                name: 'status_id'
            },
            ]
        })
    </script>
@endpush
