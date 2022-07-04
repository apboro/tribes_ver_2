@extends('common.community.profile')

@section('tab')
    <section data-tab="knowledgeBaseAddPage">
        <div class="row">
            <div class="col-12">
                <form id="knowledge_form_{{$community->id}}" action="{{ route('knowledge.store', $community)}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $answer->id ?? null }}">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-7 col-sm-7 col-md-7 col-lg-5">    
                                <h4 class="card-title">
                                    {{ __('knowledge.add_question_answer') }}
                                </h4>
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
                            <!-- Answer -->
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label" for="answer">
                                        {{ __('base.answer') }}
                                    </label>
                                    
                                    <div class="position-relative">
                                        <textarea
                                            class="form-control"
                                            id="answer"
                                            name="answer"
                                            rows="5"
                                            placeholder="{{ __('form.answer_text') }}"
                                        >{{ isset($answer) ? $answer->title : '' }}</textarea>
                                        
                                        <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                                            <i data-feather='save' class="font-medium-1" ></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            
                            <!-- Qusetion(s) -->
                            <div class="row">
                                <div class="col-md-12" id="question_controller">
                                    <label class="form-label">
                                        {{ __('knowledge.questions') }}
                                    </label>
                                    
                                    <div id="questions_container">
                                        @if(isset($answer))
                                            @foreach($answer->questions()->get() as $question)
                                                <div class="row mb-1">
                                                    <div class="col-9 col-sm-10 col-xl-10">
                                                        <div class="position-relative">
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="questions[{{ $question->id }}][title]"
                                                                value="{{ $question->title }}"
                                                                placeholder="{{ __('knowledge.question_text') }}"
                                                            >

                                                            <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                                                                <i data-feather='save' class="font-medium-1" ></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-3 col-sm-2 col-xl-2 text-end">
                                                        <span class="btn btn-danger px-1" >
                                                            <i data-feather='trash' class="font-medium-1 pe-none"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row mb-1">
                                                <div class="col-9 col-sm-10 col-xl-10">
                                                    <div class="position-relative">
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            name="questions[][title]"
                                                            value=""
                                                            placeholder="{{ __('knowledge.question_text') }}"
                                                        >

                                                        <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                                                            <i data-feather='save' class="font-medium-1" ></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-3 col-sm-2 col-xl-2 text-end">
                                                    <span class="btn btn-danger px-1" >
                                                        <i data-feather='trash' class="font-medium-1 pe-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-12">
                                        <span
                                            class="btn btn-success"
                                            onclick="CommunityPage.knowledgeBaseAddPage.questionController.addQuestion()"
                                        >
                                            <i data-feather='plus' class="font-medium-1"></i>
                                            <span>
                                                {{ __('knowledge.add_question') }}
                                            </span>    
                                        </span>
                                    </div>                                        
                                </div>
                            </div>
                        </div>
                            
                        <div class="card-footer">    
                            <div class="col-sm-5 col-lg-4 col-xl-3">
                                <button
                                    class="btn w-100 btn-icon btn-success d-flex align-items-center justify-content-center"
                                    type="submit"
                                >
                                    <i data-feather="save" class="font-medium-1"></i>
                                    <span class="ms-1">
                                        {{ __('base.save') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
