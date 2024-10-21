<div class="form-group">
    {!! Form::label('device_name', 'Nama Device', ['class' => 'mt-2']) !!}
    {!! Form::text('device_name', null, ['class' => 'form-control', 'id' => 'device_name']) !!}
</div>

<div class="form-group">
    {!! Form::label('device_code', 'Kode Device', ['class' => 'mt-2']) !!}
    {!! Form::text('device_code', null, ['class' => 'form-control', 'id' => 'device_code']) !!}
</div>

<div class="form-group">
    {!! Form::label('ip_address', 'IP Address', ['class' => 'mt-2']) !!}
    {!! Form::text('ip', null, ['class' => 'form-control', 'id' => 'ip_address']) !!}
</div>

<div class="form-group">
    {!! Form::label('mode', 'Mode', ['class' => 'mt-2']) !!}
    {!! Form::select('mode', ['1' => 'Online', '0' => 'Offline'], null, ['class' => 'form-control', 'id' => 'mode']) !!}
</div>