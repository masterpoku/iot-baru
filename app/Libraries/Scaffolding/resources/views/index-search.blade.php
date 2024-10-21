@php
    try {
        /** @var \Scaffolding\Scaffolding $scaffolding */
        $scaffolding = app('scaffolding');
        $config = $scaffolding->datatable();
        $columns = $scaffolding->datatableColumns();
        $model = $scaffolding->getModel();
        $fields = $model->fields();
    } catch (\Exception $e) {
        dd($e);
    }
@endphp

<div style="display: none" class="card list-search-container-1">
    <div class="card-body">
        {!! Form::open(['id' => 'scaffolding-datatable-form', 'method' => 'get']) !!}
        <div class="row">
            @foreach ($columns as $column => $attributes)
                @if ($attributes['searchable'])
                    @php
                        $searchAttributes = $attributes['searchAttributes'] ?? [];
                    @endphp
                    <div class="col-xl-3 col-md-6 col-12 mb-1">
                        <div class="form-group">
                            {!! Form::label($column, $attributes['title'], ['class' => 'active']) !!}
                            @if ($attributes['searchFormatter'] instanceof \Closure)
                                {!! call_user_func($attributes['searchFormatter'], $model) !!}
                            @elseif($attributes['searchType'] == 'select')
                                @php
                                    $options = [];
                                    if (is_iterable($attributes['searchOptions'])) {
                                        $options = $attributes['searchOptions'];
                                    } elseif ($attributes['searchOptions'] instanceof \Closure) {
                                        $options = call_user_func($attributes['searchOptions']);
                                    }
                                    $methodOption = 'get' . \Str::studly($column) . 'Options';
                                    if (!$options && method_exists($model, $methodOption)) {
                                        $options = $model->$methodOption();
                                    }
                                @endphp
                                {!! Form::select(
                                    "$column",
                                    $options,
                                    $attributes['value'] ?: null,
                                    [
                                        'class' =>
                                            'custom-select init-select2 browser-default' . ($attributes['className'] ? " $attributes[className]" : ''),
                                        'data-allow-clear' => 'true',
                                        'data-placeholder' => 'All',
                                    ] + $searchAttributes,
                                ) !!}
                            @else
                            @endif
                            {!! Form::text(
                                "$column",
                                null,
                                [
                                    'placeholder' => 'Search',
                                    'class' =>
                                        $attributes['searchType'] . ($attributes['className'] ? " $attributes[className]" : '') . ' form-control',
                                ] + $searchAttributes,
                            ) !!}

                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="card-footer list-search-container-2">
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn-sm btn-warning mr-1">
                <i data-feather='search'></i> Search
            </button>
            <button type="reset" class="btn-sm btn-info">
                <i data-feather='refresh-ccw'></i> Reset
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
