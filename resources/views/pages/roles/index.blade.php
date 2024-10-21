@push('css_vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('template/app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush
@extends('layouts.app')
@section('title', 'User Permissions List')
@section('breadcrumb')
    @parent
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>User List Permissions</h4>
                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#onshow">Create & Edit Permission</button>
            </div>
            <div class="card-body">
                <table id="roles-table"  class="display table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- modal  --}}
    <div class="modal fade text-left"  id="onshow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel21" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel21">Create & Edit Permissions</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="permissions-form">
                    @csrf
                <div class="modal-body">
                    {!! Form::label('', 'Username', []) !!}
                    {!! Form::select('user_id', [], null, [
                        'class' => 'custom-select browser-default init-select2 user_id',
                        'data-model' => 'user',
                        'data-label' => 'name+email'
                        ]) !!}

                <div id="permissions-section" class="mt-3" style="display: none;">
                    <label for="permissions">Permissions</label>
                    <div class="row mt-2">
                        @foreach ($permissions as $group => $perms)
                            <div class="col-lg-3 mt-1">
                                <label for="">{{ ucfirst($group) }}</label>
                                <div class="custom-control custom-control-warning custom-checkbox">
                                    <input type="checkbox" class="custom-control-input select-all-group" data-group="{{ $group }}" id="colorCheck{{$group}}"  />
                                    <label class="custom-control-label" for="colorCheck{{$group}}">All {{ $group }}</label>
                                </div>
                                @foreach ($perms as $permission)
                                    <div class="form-check">
                                        <div class="custom-control custom-control-warning custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" data-group="{{ $group }}" data-permission-id="{{ $permission->id }}" class="custom-control-input form-check-input permission-checkbox" id="colorCheck4{{$permission->id}}"/>
                                            <label class="custom-control-label form-check-label" for="colorCheck4{{$permission->id}}">{{ ucwords(str_replace('-', ' ', $permission->name)) }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                           
                        @endforeach
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Accept</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
{{-- endModal --}}
@endsection
@push('script_vendor')
    <script src="{{ asset('template/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endpush
@push('script')
<script src="{{ asset('template/app-assets/js/scripts/forms/form-select2.js') }}"></script>
{!! Html::script('template/assets/js/asset.js') !!}
<script type="text/javascript">
    $(document).ready(() => Asset.initSelect2());
</script>
<script>
    $(document).ready(function() {
        $('#roles-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('roles.data') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'permissions', name: 'permissions', orderable: false, searchable: false }
            ]
        });

        $('.user_id').on('change', function() {
            var userId = $(this).val();
            if (userId) {
                $('#permissions-section').show();
                    $.ajax({
                    url: `/get-user-permissions`,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        userId: userId,
                    },
                    success: function(data) {
                        $('.permission-checkbox').prop('checked', false);
                        $.each(data.permissions, function(index, permissionId) {
                            $(`.permission-checkbox[data-permission-id="${permissionId}"]`).prop('checked', true);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching permissions:", error);
                    }
                });
            } else {
                $('#permissions-section').hide();
            }
        });
        $('#permissions-form').on('submit', function(e) {
            e.preventDefault();
            
            var userId = $('.user_id').val(); 
            
            var checkedPermissions = $('.permission-checkbox:checked').map(function() {
                return $(this).val(); 
            }).get();

            $.ajax({
                url: '{{ route("permissions.ajax.assign") }}',
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                data: JSON.stringify({
                    user_id: userId,
                    permissions: checkedPermissions
                }),
                success: function(data) {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Permissions berhasil diassign ke user.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            $('#onshow').modal('hide'); 
                        }).then(function() {
                            location.reload();  
                        });
                    } else {
                        Swal.fire({
                            title: 'Terjadi kesalahan!',
                            text: 'Silakan coba lagi.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Terjadi kesalahan!',
                        text: 'Kesalahan pada server.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        $('.select-all-group').on('change', function() {
            var group = $(this).data('group');
            var checked = $(this).prop('checked');

            $(`.permission-checkbox[data-group="${group}"]`).prop('checked', checked);
        });
    });
    </script>

@endpush