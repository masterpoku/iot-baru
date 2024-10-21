@extends('layouts.app')

@section('title', 'Create Roles & Permissions')
@section('breadcrumb')
    @parent
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>@yield('title')</h4>
        </div>
        
        <div class="card-body">
            <form id="permissions-form">
                @csrf
                <div class="form-group">
                    <label for="user">Pilih User</label>
                    <select name="user_id" id="user" class="form-control" required>
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="permissions-section" class="mt-3" style="display: none;">
                    <label for="permissions">Permissions</label>
                    <div class="row mt-2">
                        @foreach ($permissions as $group => $perms)
                            <div class="col-lg-3 mt-1">
                                <h4 class="mt-1">{{ ucfirst($group) }}</h4>
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
                    {{-- @foreach ($permissions as $group => $perms)
                    <h4 class="mt-1">{{ ucfirst($group) }}</h4>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="custom-control custom-control-warning custom-checkbox">
                                    <input type="checkbox" class="custom-control-input select-all-group" data-group="{{ $group }}" id="colorCheck{{$group}}"  />
                                    <label class="custom-control-label" for="colorCheck{{$group}}">All {{ $group }}</label>
                                </div>
                            </div>
                            @foreach ($perms as $permission)
                                <div class="col-lg-2">
                                    <div class="form-check">
                                        <div class="custom-control custom-control-warning custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" data-group="{{ $group }}" data-permission-id="{{ $permission->id }}" class="custom-control-input form-check-input permission-checkbox" id="colorCheck4{{$permission->id}}"/>
                                            <label class="custom-control-label form-check-label" for="colorCheck4{{$permission->id}}">{{ ucwords(str_replace('-', ' ', $permission->name)) }}</label>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                             @endforeach
                        </div>
                    @endforeach --}}
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('user').addEventListener('change', function() {
            var userId = this.value;
            if (userId) {
                document.getElementById('permissions-section').style.display = 'block';
                fetch(`/get-user-permissions/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                        data.permissions.forEach(function(permissionId) {
                            document.querySelector(`.permission-checkbox[data-permission-id="${permissionId}"]`).checked = true;
                        });
                    });
            } else {
                document.getElementById('permissions-section').style.display = 'none';
            }
        });
        document.getElementById('permissions-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var userId = document.getElementById('user').value;
            var checkedPermissions = Array.from(document.querySelectorAll('.permission-checkbox:checked')).map(function(el) {
                return el.value;
            });
            fetch('{{ route("permissions.ajax.assign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    user_id: userId,
                    permissions: checkedPermissions
                })
            })
            .then(response => response.json())
            .then(data => {
    if (data.success) {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Permissions berhasil diassign ke user.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
            } else {
                Swal.fire({
                    title: 'Terjadi kesalahan!',
                    text: 'Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Terjadi kesalahan!',
                text: 'Kesalahan pada server.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });

        });
        document.querySelectorAll('.select-all-group').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                let group = this.dataset.group;
                let checked = this.checked;

                document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`).forEach(function (checkbox) {
                    checkbox.checked = checked;
                });
            });
        });
    });
</script>
@endpush
