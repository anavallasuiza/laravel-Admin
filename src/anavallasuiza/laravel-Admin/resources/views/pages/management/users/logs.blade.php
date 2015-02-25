<table class="table datatable">
    <thead>
        <tr>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Table') }}</th>
            <th>{{ __('Id') }}</th>
            <th>{{ __('Action') }}</th>
            <th>{{ __('Description') }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach($rows as $row)
        <tr>
            <td>{{ $row->created_at }}</td>

            @if ($row->related_table)
            <td><a href="{{ route('admin::'.$row->related_table.'.index') }}">{{ $row->related_table }}</a></td>
            <td><a href="{{ route('admin::'.$row->related_table.'.edit', $row->related_id) }}">{{ $row->related_id }}</td>
            @else
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            @endif

            <td>{{ $row->action }}</td>
            <td>{{ $row->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
