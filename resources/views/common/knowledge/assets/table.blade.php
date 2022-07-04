<table class="dt-complex-header table table-bordered table-responsive">
    <thead>
        <tr>
            <th rowspan="1">
                {{ __('knowledge.question') }}
            </th>
            
            <th colspan="1">
                {{ __('base.created') }}
            </th>
            
            <th rowspan="1"></th>
        </tr>
    </thead>

    <tbody>
        @if (!count($questions))
            <tr class="odd">
                <td valign="top" colspan="5" class="dataTables_empty">
                    Записи отсутствуют
                </td>
            </tr>
        @else
            @foreach($questions as $question)
                <tr class="odd">
                    <td valign="top p-0" colspan="1" class="dataTables_empty">
                        {{ $question->title }}
                    </td>

                    <td valign="top p-0" colspan="1" class="dataTables_empty">
                        {{ $question->created_at->format('d.m.y') }}
                    </td>

                    <td valign="top p-0" colspan="1" class="dataTables_empty">
                        <div class="d-inline-flex">
                            <a
                                class="dropdown-toggle hide-arrow text-primary"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <i data-feather='more-vertical' class="font-medium-6"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{ route('knowledge.edit', ['community' => $community, 'question' => $question]) }}" class="dropdown-item">
                                    <i data-feather='edit-2' class="font-medium-1"></i>
                                    <span class="">
                                        {{ __('base.edit') }}
                                    </span>
                                </a>

                                <a href="javascript:;" class="dropdown-item">
                                    <i data-feather='edit-2' class="font-medium-1"></i>
                                    <span class="">
                                        {{ __('knowledge.to_archive') }}
                                    </span>
                                </a>

                                <a href="javascript:;" class="dropdown-item delete-record">
                                    <i data-feather='trash-2' class="font-medium-1"></i>
                                    <span class="">
                                        {{ __('base.remove') }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
