<table class="table datatable">
    <thead>
        <tr>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Ip') }}</th>
            <th>{{ __('Success') }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach($rows as $row)
        <tr>
            <td>{{ $row->created_at }}</td>
            <td>{{ $row->ip }}</td>
            <td>{{ $row->success ? __('Yes') : __('No') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
