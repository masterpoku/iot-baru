@extends('layouts.app')
@section('title', 'User')
@section('breadcrumb')
    @parent
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Form input User</h4>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'user.create', 'method' => 'PUT', 'id' => 'user-form', 'files' => true]) !!}
            @includeIf('pages.user.form')
        </div>
        <div class="card-footer">
            {!! Form::button(__('Save'), ['class' => 'btn btn-sm btn-success', 'type' => 'submit']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('script')
    {!! Html::script('template/assets/js/asset.js') !!}
    {!! Html::script('template/assets/js/asset-user.js') !!}
    <script>
        $(document).ready(function() {
            User.initForm();
        });
    </script>
@endpush
