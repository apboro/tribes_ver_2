<table class="dt-complex-header table table-bordered table-responsive">
    <thead>
        <tr>
            <th rowspan="1" width="50">
                {{-- {{ __('base.avatar') }} --}}
                Логин
            </th>

            <th colspan="1">
                {{ __('base.user') }}
            </th>

            <th colspan="1">
                Сообщество
            </th>

            <th colspan="1" width="200">
                {{ __('audience.tariff_status') }}
            </th>

            <th colspan="1" width="200">
                {{ __('audience.days_left') }}
            </th>

            <th colspan="1" width="50">
                {{ __('base.added') }}
            </th>

            {{-- <th colspan="1" width="50">
                {{ __('audience.transactions') }}
            </th> --}}

            <th rowspan="1" width="20"></th>
        </tr>
    </thead>

    <tbody>
        @if (!count($followers))
            <tr class="odd">
                <td valign="top" colspan="7" class="dataTables_empty">
                    {{ __('base.no_entries') }}
                </td>
            </tr>
        @else
            @foreach ($followers as $follower)
                @foreach ($communityes as $community)
                    <tr class="odd">
                        <td valign="top p-0" colspan="1" class="dataTables_empty">
                            <a href="{{$follower->user_name ? 'https://t.me/' . $follower->user_name : ''}}" class="btn-link">
                                {{ $follower->user_name ? $follower->user_name : '' }}
                            </a>
                            {{-- <div class="" style="width: 50px;">
                            <img src="/images/no-user-avatar.svg" alt="" class="w-100">
                        </div> --}}
                        </td>

                        <td valign="top p-0" colspan="1" class="dataTables_empty">

                            {{ $follower->first_name ? $follower->first_name . ' ' : '' }}
                            {{ $follower->last_name ? $follower->last_name : '' }}

                        </td>

                        <td valign="top p-0" colspan="1" class="dataTables_empty">
                            <a href="{{ route('community.statistic', ['community' => $community->id]) }}" class="btn-link">
                                {{ $community->title ? $community->title : '' }}
                            </a>
                        </td>

                        <td valign="top p-0" colspan="1" class="dataTables_empty">
                            @if ($follower->checkStatusTariff($community->tariff->id))
                                {{ $follower->checkStatusTariff($community->tariff->id) }}
                            @endif
                        </td>

                        <td valign="top p-0" colspan="1" class="dataTables_empty">
                            @if ($follower->getTariffById($community->tariff->id))
                                {{ $follower->getTariffById($community->tariff->id)->pivot->days }}
                            @endif
                        </td>

                        <td valign="top p-0" colspan="1" class="dataTables_empty">
                            {{ $follower->payment->last() ? $follower->payment->last()->created_at->format('d.m.Y') : '-' }}
                        </td>

                        {{-- <td valign="top p-0" colspan="1" class="dataTables_empty">
                        ?
                    </td> --}}

                        <td valign="top p-0" colspan="1" class="dataTables_empty">
                            <div class="d-inline-flex">
                                <a class="dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i data-feather='more-vertical' class="font-medium-6"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    {{-- <a href="" class="dropdown-item">
                                    <i data-feather='user-plus' class="font-medium-1"></i>
                                    <span class="">
                                        {{ __('audience.add_days') }}
                                    </span>
                                </a> --}}

                                    {{-- <a href="{{ route('audience.ban', ['community' => $community, 'follower' => $follower]) }}" class="dropdown-item">
                                    <i data-feather='user-minus' class="font-medium-1"></i>
                                    <span class="">
                                        {{ __('audience.ban') }}
                                    </span>
                                </a> --}}

                                    <a href="{{ route('audience.delete', ['communityes' => $communityes, 'follower' => $follower]) }}"
                                        class="dropdown-item delete-record">
                                        <i data-feather='user-x' class="font-medium-1"></i>
                                        <span class="">
                                            {{ __('base.remove') }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @endif
    </tbody>

    <tfoot>
        <tr>
            <th rowspan="1" width="50">
                {{-- {{ __('base.avatar') }} --}}
                Логин
            </th>

            <th colspan="1">
                {{ __('base.user') }}
            </th>

            <th colspan="1">
                Сообщество
            </th>

            <th colspan="1" width="200">
                {{ __('audience.tariff_status') }}
            </th>

            <th colspan="1" width="200">
                {{ __('audience.days_left') }}
            </th>

            <th colspan="1" width="50">
                {{ __('base.added') }}
            </th>

            {{-- <th colspan="1" width="50">
                {{ __('audience.transactions') }}
            </th> --}}

            <th rowspan="1" width="20"></th>
        </tr>
    </tfoot>
</table>
