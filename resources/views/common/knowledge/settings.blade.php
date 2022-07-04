@extends('common.community.profile')

@section('tab')
    <section data-tab="knowledgeBasePageSettings">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-6 col-lg-4">    
                            <h4 class="card-title">{{ __('knowledge.knowledge_base_settings') }}</h4>
                        </div>

                        <a
                            href="{{ route('knowledge.list', $community) }}"
                            class="btn btn-outline-primary custom waves-effect"
                        >
                            <i data-feather="arrow-left" class="font-medium-1"></i>
                            <span class="align-middle d-sm-inline-block d-none">
                                {{ __('base.back') }}
                            </span>
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="">
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
