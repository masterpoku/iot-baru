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
                                <!-- Tombol Delete -->
                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}">Delete</button>

                                <!-- Formulir Delete (disembunyikan) -->
                                <form id="form-delete-{{ $item->id }}" action="{{ route('permission.delete', $item->id) }}" method="post" style="display: none;">
                                    @csrf
                                    @method('delete')
                                </form>
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

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    // Event listener untuk tombol delete
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            var permissionId = this.getAttribute('data-id');

            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this permission!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // Submit form delete setelah konfirmasi
                        document.getElementById('form-delete-' + permissionId).submit();
                    } else {
                        swal("Your permission is safe!");
                    }
                });
        });
    });
</script>
@endpush