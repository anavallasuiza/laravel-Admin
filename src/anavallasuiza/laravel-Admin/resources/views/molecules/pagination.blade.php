<div class="box-footer clearfix">
    <div class="row">
        <div class="col-sm-8 text-center">
            {!! method_exists($list, 'render') ? str_replace('/?', '?', $list->appends(request()->except('page'))->render()) : '' !!}
        </div>

        <div class="col-sm-2">
            <select name="f-rows" class="form-control" data-change-submit>
                <option value="20" {{ ($paginate === 20) ? 'selected' : '' }}>20</option>
                <option value="50" {{ ($paginate === 50) ? 'selected' : '' }}>50</option>
                <option value="100" {{ ($paginate === 100) ? 'selected' : '' }}>100</option>
                <option value="200" {{ ($paginate === 200) ? 'selected' : '' }}>200</option>
                <option value="-1" {{ empty($paginate) ? 'selected' : '' }}>{{ __('All') }}</option>
            </select>
        </div>
    </div>
</div>
