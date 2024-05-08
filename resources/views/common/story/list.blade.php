@extends('layouts.app')
@section('content')

<div class="col-12">
    <div class="row">

        <h1>Список историй</h1>
        <a class="btn btn-primary col-6  mt-2" href="{{ route('stories.create')}}"">Добавить историю</a>
        <ul class="list-group mt-2">
            @foreach ($stories as $story)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-8">
                        <b>{{ $story->name }}</b>
                        <p>{{ $story->content }}</p>
                        <div class="alert alert-secondary px-1" role="alert">
                            <p>ID: {{ $story->id }}</p>
                            <p>Button: {{ $story->button }}</p>
                            <p>Link: {{ $story->link }}</p>
                        </div>
                        <form method="POST" action="{{ route('stories.destroy', $story->id)}}" class="mt-1">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                    <div class="col-4">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($story->image) }}" style="max-width:100%;">
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection