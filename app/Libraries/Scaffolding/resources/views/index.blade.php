@php
    try {
        $config = $scaffolding->datatable();
        $columns = $scaffolding->datatableColumns();
        $fields = $model->fields();
        $html5Attr = collect($config)
            ->transform(function ($value, $key) {
                if (in_array($key, ['viewSearch', 'withQuery', 'viewScript'])) {
                    return '';
                }
                if ((!is_bool($value) && !$value) || $value instanceof \Closure) {
                    return '';
                }
                $key = str_replace('_', '-', Str::snake($key));
                if (is_array($value) || is_bool($value)) {
                    $value = json_encode($value);
                }
                return "data-$key='$value'";
            })
            ->implode(' ');
    } catch (\Exception $e) {
        dd($e);
    }
@endphp

@push('css')
<style>
   table.dataTable th {
    background: #00cfe8b0 !important;
    color: #fff;
    border: none !important;
    border-bottom: 5px solid #00cfe8 !important;
    text-align: center;
    border-radius: 0;
}
</style>
@endpush
@push('css_vendor')
@endpush
@extends('layouts.app')
@section('title', $title)
@section('breadcrumb')
@parent
@endsection


{{-- page content --}}
@section('content')
{!! $config['viewStyle'] ? implode("\n", $config['viewStyle']) : '' !!}
{!! is_array($config['viewSearch']) ? implode("\n", $config['viewSearch']) : $config['viewSearch'] !!}
    <div class="card">
        <div class="card-body">
            {!! $config['responsive'] ? '<div class="responsive-table">' : '' !!}
                <table style="width: 100%" id="datatable_{{str_replace('.', '_', $prefix)}}"
                class="table invoice-data-table white border-radius-4 pt-1 striped scaffolding-datatable{{$config
                ['init'] ? '' : '-false'}}" {!! $html5Attr !!}>
                <thead>
                    <tr>
                        {!! $config['checkbox'] ? '<th></th>' : '' !!}
                        @foreach ($columns as $column => $attributes)
                            @php
                                $dataAttr = collect($attributes)
                                    ->transform(function ($value, $key) {
                                        if ((!is_bool($value) && !$value) || $value instanceof \Closure) {
                                            return '';
                                        }
                                        $key = str_replace('_', '-', Str::snake($key));
                                        if (is_array($value) || is_bool($value)) {
                                            $value = json_encode($value);
                                        }
                                        return "data-$key='$value'";
                                    })
                                    ->implode(' ');
                            @endphp
                            <th {!! $dataAttr !!}>{{ $attributes['title'] }}</th>
                        @endforeach
                    </tr>
                    @if ($config['columnSearch'])
                        <tr>
                            {!! $config['checkbox'] ? '<th></th>' : '' !!}
                            @foreach ($columns as $column => $attributes)
                                <th style="padding: 0 5px">
                                    @if ($attributes['searchable'])
                                        @if ($attributes['searchType'] == 'select')
                                            @php
                                                $options = [];
                                                if (is_array($attributes['searchOptions'])) {
                                                    $options = $attributes['searchOptions'];
                                                } elseif ($attributes['searchOptions'] instanceof \Closure) {
                                                    $options = call_user_func($attributes['searchOptions']);
                                                }
                                                $methodOption = 'get' . \Str::studly($column) . 'Options';
                                                if (!$options && method_exists($model, $methodOption)) {
                                                    $options = $model->$methodOption();
                                                }
                                            @endphp
                                            {!! Form::select("search_$column", $options, null, ['class' => 'select2x browser-defaultx']) !!}
                                        @else
                                            {!! Form::text("search_$column", null, ['placeholder' => 'search', 'class' => $attributes['searchType']]) !!}
                                        @endif
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    @endif
                </thead>
            </table>
            {!! $config['responsive'] ? '</div>' : '' !!}
            {!! $config['viewScript'] ? implode("\n", $config['viewScript']) : '' !!}
        </div>
        <div class="card-footer">

        </div>
    </div>
@endsection
@push('script_vendor')
<script src="{{asset('template/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('template/src/js/scripts/scaffolding.js')}}"></script>
@endpush
@push('script')
@endpush
