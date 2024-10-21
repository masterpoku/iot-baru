<div class="input-field">
    {!! Form::label('name', 'Nama Pengguna', ['class' => 'mt-2']) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
</div>


<div class="input-field">
{!! Form::label('email_user', 'Email', ['class' => 'mt-2']) !!}
{!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
</div>
<div class="input-field">
{!! Form::label('password', 'password', ['class' => 'mt-2']) !!}
{!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
</div>
<div class="input-field">
{!! Form::label('', 'Role', ['class' => 'mt-2'], false) !!}
{!! Form::select('role', $roles, null, ['class' => 'form-control']) !!}
</div>