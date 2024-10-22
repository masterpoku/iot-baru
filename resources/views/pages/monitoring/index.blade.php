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
                            <input type="checkbox" name="main_checkbox" class="custom-control-input" id="colorCheckq5" />
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
        ajax: "{{ route('monitoring.index') }}",
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
    }).on('draw', function() {
        $('input[name="country_checkbox"]').each(function() {
            this.checked = false;
        });
        $('input[name="main_checkbox"]').prop('checked', false);
        $('button#deleteAllBtn').addClass('d-none');
    });
    $(document).on('click', 'input[name="main_checkbox"]', function() {
        if (this.checked) {
            $('input[name="country_checkbox"]').each(function() {
                this.checked = true;
            });
        } else {
            $('input[name="country_checkbox"]').each(function() {
                this.checked = false;
            });
        }
        toggledeleteAllBtn();

    });
    $(document).on('change', 'input[name="country_checkbox"]', function() {
        if ($('input[name="country_checkbox"]').length == $('input[name="country_checkbox"]:checked').length) {
            $('input[name="main_checkbox"]').prop('checked', true);
        } else {
            $('input[name="main_checkbox"]').prop('checked', false);
        }
        toggledeleteAllBtn();
    });

    function toggledeleteAllBtn() {
        if ($('input[name="country_checkbox"]:checked').length > 0) {
            $('button#deleteAllBtn').text('Delete (' + $('input[name="country_checkbox"]:checked').length + ')')
                .removeClass('d-none');
        } else {
            $('button#deleteAllBtn').addClass('d-none');
        }
    }
    $(document).on('click', 'button#deleteAllBtn', function() {
        var checked = [];
        $('input[name="country_checkbox"]:checked').each(function() {
            checked.push($(this).data('id'));
            console.log(checked)
        });

        if (checked.length > 0) {
            swal.fire({
                title: 'Are you sure?',
                html: 'You want to delete <b>(' + checked.length + ')</b> <strong>Data Sensor</strong>',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#d33',
                width: 300,
                allowOutsideClick: false
            }).then(function(result) {
                if (result.value) {
                    $.post(url, {
                        countries_ids: checked
                    }, function(data) {
                        if (data.code == 1) {
                            $('#mont_datatable').DataTable().ajax.reload(null, true);
                            toastr.success(data.msg);
                        }
                    }, 'json');
                }
            })
        }
    });
    $(document).on('click', '#deleteSensorBtn', function() {
        var sensor_id = $(this).data('id');

        swal.fire({
            title: 'Are you sure?',
            html: 'You want to <b>delete</b> this Sensor',
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes, Delete',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#556ee6',
            width: 450,
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.post(url, {
                    sensor_id: sensor_id
                }, function(data) {
                    if (data.code == 1) {
                        $('#mont_datatable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                }, 'json');
            }
        });
    });
</script>
@endpush