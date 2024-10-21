@extends('layouts.app')
@section('title', 'User Edit')
@section('breadcrumb')
    @parent
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h4>Form Edit User</h4>
    </div>
    <div class="card-body">
        {!! Form::model($model, [
            'method' => 'PATCH',
            'route' => ['user.edit', $model->id],
            'id' => 'user-form',
        ]) !!}
        @if(isset($model->id))
        {!! Form::hidden('id', $model->id) !!}
        @endif
        @include('pages.user.form')
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col d-flex justify-content-start">
                {!! Form::open(['url' => '', 'method' => 'post']) !!}
                {!! Form::button(__('Update'), ['class' => 'btn btn-warning', 'type' => 'submit']) !!}
                {!! Form::close() !!}
            </div>
        </div>
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
