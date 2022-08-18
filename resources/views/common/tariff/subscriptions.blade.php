@extends('common.community.profile')

@section('tab')
    <section data-tab="subscriptionPage">
        <div class="row">
            <div class="col-12">
                <!-- Form error -->
                @if ($errors->any())
                    <div class="col-12">
                        <div role="alert" class="alert alert-danger">
                            <div class="alert-body">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>
                                            <p>{{ $error }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @include('common.tariff.assets.subscription_form')

            </div>
        </div>
    </section>
@endsection
