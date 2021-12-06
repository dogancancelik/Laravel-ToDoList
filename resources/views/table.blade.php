<table class="table">
    @if($list)
        @foreach($list as $value)
            <tr data-id="{{ $value->id }}" data-status="{{ $value->status }}" class="@if($value->status == 1) bg-success @endif">
                <td class="click-task cursor-pointer">
                    @if($value->status == 1) <i class="far fa-check-square font-size-1-5-r"></i> @else <i class="far fa-square font-size-1-5-r"></i> @endif
                </td>
                <td class="task_text @if($value->status == 1) text-decoration-line-through @endif">
                    {{ $value->task }}
                </td>
                <td class="task-operations">
                    <a class="text-info edit-task">
                        <i class="far fa-edit"></i>
                    </a>
                    <a class="text-danger delete-task" href="#">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    @endif
</table>
