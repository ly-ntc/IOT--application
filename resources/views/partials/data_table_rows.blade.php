
@foreach ($allData as $index => $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->temperature }}</td>
        <td>{{ $item->humidity }}</td>
        <td>{{ $item->light }}</td>
        <td>{{ $item->time }}</td>
    </tr>
@endforeach

