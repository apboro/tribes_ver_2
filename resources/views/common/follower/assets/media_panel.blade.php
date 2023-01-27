<div class="media_panel" id="media_panel">
    <div class="media-views__product">
        <h5 class="media-views__header">Медиатовар</h5>
        <span class="media-views__title">{{ $course->title }}</span>
        <div class="media-views__info">
{{--            {{ dd($course) }}--}}
            @if($course->owner == Auth::user()->id){{--автор--}}
                <p class="media-views__info-item"><b>Автор:</b> мой курс @if ($course->isPublished == true) опубликовано @else не опубликовано @endif </p>
                <p class="media-views__info-item"><b>Предпросмотр</b></p>
                <p class="media-views__info-item"><b>Дата создания: </b>
                    {{ date('d.m.Y', strtotime($course->created_at)) }}
                </p>
                <p class="media-views__info-item"><b>Стоимость:</b> {{ $course->cost }} руб.</p>
                <p class="media-views__info-item"><b>Срок действия:</b>
                    @if ($course->isEthernal == true)
                        Бессрочный
                    @else
                        {{ $course->access_days }} дн.
                    @endif
                </p>
            @else{{--покупатель--}}

                <p class="media-views__info-item"><b>Автор:</b> {{ $course->author()->first()->name ?? 'null' }} </p>
                <p class="media-views__info-item"><b>Дата покупки:</b>
                    {{ date('d.m.Y', strtotime($course->buyers()->find(Auth::user()->id)->pivot->byed_at)) }}
                </p>
                <p class="media-views__info-item"><b>Стоимость:</b> {{ $course->buyers()->find(Auth::user()->id)->pivot->cost }} руб.</p>
                <p class="media-views__info-item"><b>Доступен до:</b>
                    {{ date('d.m.Y', strtotime($course->buyers()->find(Auth::user()->id)->pivot->expired_at)) }}
                </p>
            @endif

        </div>
    </div>

        @if($course->getOrderedLessons()->count() > 1)
        <div class="dropdown-divider m-0"></div>
        <div class="media-views__product">
            <h5 class="media-views__header">Структура контента</h5>

            <ol class="media-views__product-list">
                @foreach ($course->getOrderedLessons() as $les)
                    <li class="media-views__product-item {{ request()->lesson == $les->id ? 'active' : '' }}">
                        <a
                            href="{{ $course->getProductWithLesson($les->id ?? 0) }}"
                            class="media-views__product-link"
                        >
                            {{ $les->title }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </div>
        @endif
    <div class="dropdown-divider m-0"></div>
    <div>
        <form action="{{ route('course.feedback', ['id' => $course->id]) }}" method="post">
            @csrf
            <div class="mt-2">
                <label for="feedback" class="form-label">Оставьте сообщение автору</label>
                <textarea name="message" class="form-control" id="feedback" placeholder="Текст сообщения" cols="30" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-1">Отправить</button>
        </form>
    </div>

</div>
