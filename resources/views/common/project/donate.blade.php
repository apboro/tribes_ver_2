@extends('layouts.project')

@section('content')

    <div class="community__communities-page" data-plugin="CommunitiesPage">
        @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-8 col-sm-6 col-md-8 col-lg-8">
                            <div class="row breadcrumbs-top">
                                <div class="col-12">
                                    <h2 class="content-header-title float-start mb-0 border-0">
                                        Донаты
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-4 col-sm-6 col-md-4 col-lg-4">
                            <div class="text-end mb-0">
                                <!--TODO community?-->
                                @if($activeCommunity)
                                <a
                                        class="btn btn-success text-white"
                                        href="{{ route('community.donate.add', $activeCommunity) }}"
                                >
                                    <i data-feather='plus' class="font-medium-1"></i>
                                    <span class="d-none d-sm-inline-block ms-1">Создать донат</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($donates))
                    <!-- Cards -->
                    <div class="row">
                        @foreach($donates as $donate)
                            @include('common.donate.assets.donate_item')
                        @endforeach
                    </div>
                @else
                    <!-- Empty list -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-12">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="text-center">
                                                Донаты не подключены
                                            </h5>
                                        </div>
                                        @if($activeCommunity)
                                        <a
                                                class="btn btn-success text-white mt-1"
                                                href="{{ route('community.donate.add', $activeCommunity) }}"
                                        >
                                            <i data-feather='plus' class="font-medium-1"></i>
                                            <span>Добавить</span>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection