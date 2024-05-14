@extends('layouts.app')
@section('content')

<div class="col-12">
    <div class="row">
        <h1>Новая история</h1>

        <div class="card col-8">
            <form method="POST" action="{{ $story ? route('stories.update', $story->id) : route('stories.store') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="card-body">

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input" placeholder="Название" name="name"
                                value="{{ $story->name ?? '' }}">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input" placeholder="Кнопка" name="button"
                                value="{{ $story->button ?? '' }}">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input" placeholder="Ссылка" name="link"
                                value="{{ $story->link ?? '' }}">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            Контент<br>
                            <textarea name="content"
                                class="form-control dt-input">{{ $story->content ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input"
                                placeholder="Сортировка (от 0, сортируем по возрастанию)" name="sort"
                                value="{{ $story->sort ?? '' }}">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input" placeholder="Градиент" name="gradient"
                                value="{{ $story->gradient ?? '' }}">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            <input type="text" class="form-control dt-input" placeholder="Блок друзей (0 или 1)"
                                name="friends" value="{{ $story->friends ?? '' }}">
                        </div>
                    </div>

                    @if (!$story->ico)
                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            Иконка<br>
                            <input type="file" name="ico">

                        </div>
                    </div>
                    @endif

                    @if (!$story->image)
                    <div class="row mt-2">
                        <div class="col-md-12 col-xl-12 mt-12">
                            Картинка<br>
                            <input type="file" name="image">
                        </div>
                    </div>
                    @endif

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

        <div class=" col-2">
            <div class="card-body">
                @if ($story->ico)
                <div class=" mt-2">
                    <div class="col-md-12 col-xl-12 mt-12">
                        Иконка<br>
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($story->ico) }}"
                            style="max-width:90%;"><br>
                        <form method="POST"
                            action="{{ route('stories.image.destroy', ['type' => 'ico', 'id' => $story->id]) }}"
                            class="mt-1">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
                @endif

                @if ($story->image)
                <div class="row mt-2">
                    <div class="col-md-12 col-xl-12 mt-12">
                        Картинка<br>
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($story->image) }}"
                            style="max-width:90%;"><br>
                        <form method="POST"
                            action="{{ route('stories.image.destroy', ['type' => 'image', 'id' => $story->id]) }}"
                            class="mt-1">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection