@extends('layouts.app')
@section('title', $title)
@section('breadcrumb')
    @parent
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <!-- Header content here -->
        </div>
        <div class="card-body">
            {!! Form::model($model, [
                'method' => $model->id ? 'patch' : 'put',
                'files' => true,
                'id' => 'scaffolding-form',
                'enctype' => 'multipart/form-data',
            ]) !!}
            <div class="row">
                @php
                    $fields = $model->fields();
                    if (!isset($columns)) {
                        $columns = $model->getColumns();
                    } elseif (isset($columns) && is_array($columns)) {
                        $columns = collect($columns);
                    }
                    $cols = isset($cols) ? $cols : 2;
                    $count = $columns->count();
                    $chunk = ceil($cols <= $count ? $count / $cols : $count);
                    $sprintName = isset($sprintName) ? $sprintName : '%s';
                    $validate = isset($validate) ? $validate : true;
                    $hiddens = isset($hiddens) ? $hiddens : [];
                    $viewOnly = isset($viewOnly) ? $viewOnly : false;
                @endphp
                @foreach ($columns as $column)
                    @php
                        $fieldName = sprintf($sprintName, $column);
                        $fieldAttr = ['class' => 'form-control'];
                    @endphp
                    @if ($fields && ($field = $fields->get($column)))
                        @php
                            $field = (object) $field;
                            $fieldType = $field->type;
                            $fieldFormatter = $field->formatter;
                            $fieldDisplay = $field->display;
                            $fieldAttr = $field->attributes ?? [];
                            if ($action != 'View') {
                                $fieldAttr['class'] = ($fieldAttr['class'] ?? '') . ' form-control mb-1';
                            }else {
                                $fieldAttr['class'] = ($fieldAttr['class'] ?? '') . ' form-control mb-1';
                                $fieldAttr['readonly'] = 'readonly';
                            }
                            $fieldLabel = $field->label;
                            $fieldValue = $model->$fieldName ? $model->$fieldName : $field->value;
                            $fieldRequired = $field->required;
                            if (is_callable($fieldValue)) {
                                $fieldValue = call_user_func($fieldValue, $model);
                            }
                            if (is_date($fieldValue)) {
                                $fieldValue = databaseToTimezoneFormat($fieldValue);
                            }
                            if ($fieldRequired) {
                                if ($validate) {
                                    $fieldAttr[] = 'required';
                                }
                                $fieldAttr['class'] .= ' required';
                            }
                            if ($fieldType == 'textarea') {
                                $fieldAttr['class'] = 'form-control';
                                $fieldAttr['rows'] = 5;
                            }
                            if ($fieldType == 'decimal') {
                                $fieldType = 'number';
                                $fieldAttr['step'] = 'any';
                            }
                            if (
                                array_key_exists('multiple', $fieldAttr) ||
                                array_search('multiple', $fieldAttr, true) !== false
                            ) {
                                $fieldName .= '[]';
                            }
                            if ($viewOnly) {
                                $fieldAttr['readonly'] = 'readonly';
                            }
                            $containerAttributes = collect($field->containerAttributes)
                                ->transform(function ($attr, $key) {
                                    if (is_string($attr)) {
                                        return "$key='$attr'";
                                    }
                                })
                                ->implode(' ');
                        @endphp
                        @if ($fieldType == 'file')
                            <div class="col s6 file-field input-field">
                                <div class="btn float-right">
                                    <span>{!! $fieldLabel !!}</span>
                                    {!! Form::file($fieldName) !!}
                                </div>
                                <div class="file-path-wrapper">
                                    {!! Form::text($fieldName, null, ['class' => 'file-path', 'disabled']) !!}
                                </div>
                            </div>
                        @else
                            <div {!! $containerAttributes !!}>
                                @if ($fieldFormatter instanceof \Closure)
                                    {!! call_user_func($fieldFormatter, compact('model')) !!}
                                @elseif($fieldFormatter)
                                    {!! $fieldFormatter !!}
                                @else
                                    <div class="input-field">
                                        @if (method_exists($model, 'getFormField') && ($viewField = $model->getFormField($column)))
                                            {!! $viewField !!}
                                        @elseif($fieldType == 'select')
                                            @php
                                                $options = $field->options;
                                                $methodOption = 'get' . \Str::studly($column) . 'Options';
                                                if (method_exists($model, $methodOption)) {
                                                    $options = $model->$methodOption();
                                                }
                                            @endphp
                                            {!! Form::label(
                                                $fieldName,
                                                $fieldLabel . ($fieldRequired ? '<span class="required">*</span>' : ''),
                                                ['class' => 'active'],
                                                false,
                                            ) !!}
                                            {!! Form::$fieldType($fieldName, $options, $fieldValue, $fieldAttr) !!}
                                        @elseif($fieldType == 'textarea')
                                            {!! Form::label($fieldName, $fieldLabel . ($fieldRequired ? '<span class="required">*</span>' : ''), [], false) !!}
                                            {!! Form::$fieldType($fieldName, $fieldValue, $fieldAttr) !!}
                                        @else
                                            {!! Form::label($fieldName, $fieldLabel . ($fieldRequired ? '<span class="required">*</span>' : ''), [], false) !!}
                                            {!! Form::input($fieldType, $fieldName, $fieldValue, $fieldAttr) !!}
                                        @endif
                                        @if ($error = $errors->first($fieldName))
                                            <div class="error">{{ $error }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                @endforeach
                @foreach ($hiddens as $hidden)
                    {!! Form::hidden($hidden, $model->$hidden) !!}
                @endforeach
            </div>
            {!! Form::close() !!}
        </div>
        <div class="card-footer">
                <button class="btn btn-info scaffolding-submit" data-target="#scaffolding-form">Save</button>
                @if (
                    $model->id &&
                        \Route::getRoutes()->hasNamedRoute("{$prefix}.delete"))
                    <button class="btn btn-danger scaffolding-submit" data-target="#scaffolding-form-delete">Delete</button>
                    {!! Form::open([
                        'method' => 'delete',
                        'url' => route("{$prefix}.delete", $model->id),
                        'id' => 'scaffolding-form-delete',
                    ]) !!}
                @endif
        </div>
    </div>
@endsection
@push('script')
    <script>
        window.dd = function() {
            window.console.log.apply(window.console, arguments);
        };
        $(document).ready(function() {
            var $form = $('#scaffolding-form');
            var $formDelete = $('#scaffolding-form-delete');
            $('select[required]').css({
                position: 'absolute',
                display: 'inline',
                height: 0,
                padding: 0,
                border: '1px solid rgba(255,255,255,0)',
                width: 0
            });
            $(document).off('click', '.scaffolding-submit').on('click', '.scaffolding-submit', function() {
                var $btn = $(this);
                var $form = $($btn.data('target'));
                $form.submit();
            });
            $form.validate({
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    var placement = $(element).data('error'),
                        $inputField = element.closest('.input-field');
                    if (placement) {
                        $(placement).append(error)
                    } else {
                        $inputField.append(error)
                    }
                }
            });
            $formDelete.submit(function() {
                if (!confirm("Are you sure")) return false;
            })
        });
    </script>
@endpush
