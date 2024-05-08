@extends('layouts.app')
@section('content')

<div class="col-12">
    <div class="row">
        <h1>Новая история</h1>

        <div class="card">
            <form method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="card-body">

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input" placeholder="Название" name="name"
                                value="">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input" placeholder="Кнопка" name="button"
                                value="">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input" placeholder="Ссылка" name="link"
                                value="">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            Контент<br>
                            <textarea name="content" class="form-control dt-input"></textarea>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            Картинка<br>
                            <input type="file" name="image">
                        </div>
                    </div>                    

                    <div class="row mt-2">
                        <div class="col-sm-4 col-lg-3 mt-1 mt-sm-0">
                            <button type="submit" class="btn btn-outline-primary waves-effect w-100">
                                Сохранить
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>
</div>
@endsection