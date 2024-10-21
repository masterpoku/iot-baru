@extends('layouts.app')
@section('title', 'Device')
@section('breadcrumb')
@parent
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h4>Form input Device</h4>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'device.create', 'method' => 'PUT', 'id' => 'device-form', 'files' => true]) !!}
        @includeIf('pages.device.form')
    </div>
    <div class="card-footer">
        {!! Form::button(__('Save'), ['class' => 'btn btn-sm btn-success', 'type' => 'submit']) !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection
@push('script')
{!! Html::script('template/assets/js/asset.js') !!}
{!! Html::script('template/assets/js/asset-device.js') !!}
<script>
    $(document).ready(function() {
        Device.initForm();
    });
</script>
@endpush