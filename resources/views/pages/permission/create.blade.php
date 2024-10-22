@push('css_vendor')
<link rel="stylesheet" type="text/css" href="{{ asset('template/app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush
@extends('layouts.app')
@section('title', 'Permission')
@section('breadcrumb')
@parent
@endsection
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Form input permission</h4>
            </div>
            <div class="card-body">
                {!! Form::open(['route' => 'permission.create', 'method' => 'PUT', 'id' => 'user-form', 'files' => true]) !!}
                <div class="form-group">
                    {!! Form::label('', 'Name permission', []) !!}
                    {!! Form::text('name', '', ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    @php
                    $options = [
                    'list' => 'List',
                    'create' => 'Create',
                    'edit' => 'Edit',
                    'delete' => 'Delete',
                    ];
                    @endphp
                    {!! Form::label('options', 'Option', []) !!}
                    {!! Form::select('options[]', $options, null, ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                </div>
            </div>
            <div class="card-footer">
                {!! Form::button(__('Save'), ['class' => 'btn btn-success', 'type' => 'submit']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>List Permission</h4>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Guard</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permission as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->guard_name }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" data-url="{{ route('permission.delete', $item->id) }}" onclick="deleteData(this)">
                                    <i data-feather='trash'></i>
                                </button>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
@push('script_vendor')
<script src="{{ asset('template/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endpush
@push('script')
<script src="{{ asset('template/app-assets/js/scripts/forms/form-select2.js') }}"></script>
{!! Html::script('template/assets/js/asset.js') !!}
{!! Html::script('template/assets/js/asset-user.js') !!}

<script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    function deleteData(button) {
        var url = $(button).data('url'); // Mengambil data-url dari button

        Swal.fire({
            title: 'PERINGATAN!',
            text: "Yakin ingin menghapus?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function() {
                        Swal.fire(
                            'Deleted!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Gagal menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>





@endpush