@extends('layouts.project')

@section('tab')
    <section
        class="analytics-community community__tab"
        data-tab="analyticsListPage"
    >
        <div class="analytics-community__analytics-wrap">
            <div class="analytics-community__title-wrap">
                <h2 class="analytics-community__title">
                    Аналитика
                </h2>
            </div>
            
            <div>
                <div class="filter-analytics-community analytics-community__filter">
                    <select
                        id="period_filter"
                        class="select-rounded"
                        onchange="CommunityPage.analyticsListPage.switchFilter(event)"
                    >
                        <option value="day">День</option>
                        <option value="week" selected>Неделя</option>
                        <option value="month">Месяц</option>
                        <option value="year">Год</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="analytics-community__tab">
            <ul class="analytics-community__card-list">
                <li class="card-analytics-community analytics-community__card-item">
                    <div class="card-analytics-community__header">
                        <h3 class="card-analytics-community__title">
                            Подписчики
                        </h3>
                        
                        <span class="card-analytics-community__subtitle">
                            за период
                        </span>
                    </div>
                    
                    <div class="card-analytics-community__chart">
                        <canvas id="subscribers_chart"></canvas>
                    </div>
                    
                    <div class="card-analytics-community__footer">
                        <div class="card-analytics-community__info">
                            <span class="card-analytics-community__info-label">
                                Покинули
                            </span>
                            <span
                                id="subscribers_left_label"
                                class="card-analytics-community__info-value card-analytics-community__info-value--red"
                            >
                                0
                            </span>
                        </div>
                        
                        <div class="card-analytics-community__info card-analytics-community__info--right">
                            <span class="card-analytics-community__info-label">
                                Вступили
                            </span>
                            <span
                                id="subscribers_right_label"
                                class="card-analytics-community__info-value card-analytics-community__info-value--green"
                            >
                                0
                            </span>
                        </div>
                        <a
                            href="{{ route('community.statistic.subscribers', $community) }}"
                            class="button-filled button-filled--primary card-analytics-community__link"
                        >
                            Подробнее
                        </a>
                    </div>
                </li>
                
                <li class="card-analytics-community analytics-community__card-item">
                    <div class="card-analytics-community__header">
                        <h3 class="card-analytics-community__title">
                            Сообщения
                        </h3>
                        
                        <span class="card-analytics-community__subtitle">
                            за период
                        </span>
                    </div>
                    
                    <div class="card-analytics-community__chart">
                        <canvas id="messages_chart"></canvas>
                    </div>
                    
                    <div class="card-analytics-community__footer">
                        <div class="card-analytics-community__info">
                            <span class="card-analytics-community__info-label">
                                Отправлено
                            </span>
                            
                            <span
                                id="messages_left_label"
                                class="card-analytics-community__info-value"
                            >
                                0
                            </span>
                        </div>
                        
                        <div class="card-analytics-community__info card-analytics-community__info--right">
                            <span class="card-analytics-community__info-label">
                                Полезных
                            </span>
                            
                            <span
                                id="messages_right_label"
                                class="card-analytics-community__info-value card-analytics-community__info-value--green"
                            >
                                0
                            </span>
                        </div>
                        
                        <a
                            href="{{ route('community.statistic.messages', $community) }}"
                            class="button-filled button-filled--primary card-analytics-community__link"
                        >
                            Подробнее
                        </a>
                    </div>
                </li>
                
                <li class="card-analytics-community analytics-community__card-item">
                    <div class="card-analytics-community__header">
                        <h3 class="card-analytics-community__title">
                            Финансы
                        </h3>
                        
                        <span class="card-analytics-community__subtitle">
                            за период
                        </span>
                    </div>
                    
                    <div class="card-analytics-community__chart">
                        <canvas id="payments_chart"></canvas>
                    </div>
                    
                    <div class="card-analytics-community__footer">
                        <div class="card-analytics-community__info">
                            <span class="card-analytics-community__info-label">
                                Всего
                            </span>
                            
                            <span
                                id="payments_left_label"
                                class="card-analytics-community__info-value"
                            >
                                0
                            </span>
                        </div>
                        
                        <div class="card-analytics-community__info card-analytics-community__info--right">
                            <span class="card-analytics-community__info-label">
                                За период
                            </span>
                            
                            <span
                                id="payments_right_label"
                                class="card-analytics-community__info-value card-analytics-community__info-value--green"
                            >
                                0
                            </span>
                        </div>
                        
                        <a
                            href="{{ route('community.statistic.payment', $community) }}"
                            class="button-filled button-filled--primary card-analytics-community__link"
                        >
                            Подробнее
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </section>
@endsection
