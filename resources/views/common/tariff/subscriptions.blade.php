@extends('common.community.profile')

@section('tab')
    <section data-tab="subscriptionPage">
        <div class="row">
            <div class="col-12">
                
                        @include('common.tariff.assets.subscription_form')
                    
            </div>
        </div>
    </section>
@endsection
