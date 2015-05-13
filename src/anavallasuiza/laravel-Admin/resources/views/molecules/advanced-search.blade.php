<form method="get">
    @foreach ($form['filters'] as $filter)
    <div class="row advanced-search">
        <div class="col-xs-12 col-sm-4 col-lg-2">
            {!! $filter['f-search-f'] !!}
        </div>

        <div class="col-xs-8 col-sm-6 col-lg-9">
            {!! $filter['f-search-q'] !!}
        </div>

        <div class="col-xs-4 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default btn-block duplicate-action-add" rel="advanced-search">
                {{ __('Add') }}
            </button>
        </div>
    </div>
    @endforeach

    <button type="submit" class="hidden">
        {{ __('Send') }}
    </button>
</form>
