@foreach ($employee as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td>{{ $item->name }}</td>
        <td>
            @if ($item->department !=  null)
                {{ $item->department->name }}
            @endif
        </td>
        <td>
            @if ($item->section !=  null)
                {{ $item->section->name }}
            @endif
        </td>
        <td>
            @if ($item->designation !=  null)
                {{ $item->designation->name }}
            @endif
        </td>
        <td>{{ date('d/m/Y',strtotime($item->joining_date)) }}</td>
        <td>Tk. {{ $item->gross_salary }}</td>
        <td>
            @if ($item->company !=  null)
                {{ $item->company->name }}
            @endif
        </td>
        <td>
            @if ($item->branch !=  null)
                {{ $item->branch->name }}
            @endif
        </td>
        <td>
            @if ($item->status == '1')
                <a href="{{ route('change-employee-status',['0',$item->id]) }}" class="text-success text-bold">Active</a>
            @else
                <a href="{{ route('change-employee-status',['1',$item->id]) }}" class="text-danger text-bold">Inactive</a>
            @endif
        </td>
        <td>
            <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}')" data-target="#modal-view" class="btn btn-sm btn-success"><i class="fa fa-search"></i></button>
            <a href="{{ route('edit-employee',[$item->id]) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
        </td>
    </tr>
@endforeach