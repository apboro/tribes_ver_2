@extends('common.community.profile')

@section('tab')
    <div
        class="analytics-community community__tab"
        data-tab="analyticsPaymentsPage"
    >
        <div class="analytics-community__analytics-wrap">
            <div class="analytics-community__title-wrap">
                <a
                    href="{{ route('community.statistic', $community) }}"
                    class="button-back"
                >
                    <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
                </a>
                
                <h2 class="analytics-community__title">
                    Аналитика
                </h2>
            </div>
            
            <div>
                <select
                    class="select-rounded analytics-community__filter"
                    onchange="CommunityPage.analyticsPaymentsPage.switchTab(event)"
                >
                    <option value="{{ route('community.statistic.subscribers', $community) }}">Подписчики</option>
                    <option value="{{ route('community.statistic.messages', $community) }}">Сообщения</option>
                    <option value="{{ route('community.statistic.payments', $community) }}" selected>Финансы</option>
                </select>
                
                <div class="filter-analytics-community analytics-community__filter">
                    <select
                        class="select-rounded"
                        onchange="CommunityPage.analyticsPaymentsPage.switchFilter(event)"
                    >
                        <option value="day">День</option>
                        <option value="week" selected>Неделя</option>
                        <option value="month">Месяц</option>
                        <option value="year">Год</option>
                        <option value="all_time">За все время</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="analytics-community__tab">
            <div class="chart-analytics-community">
                <div class="chart-analytics-community__header">
                    <button class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right">
                        <span class="chart-analytics-community__text" style="color: rgb(54, 52, 64);">
                            Донаты
                        </span>
                        
                        <span class="chart-analytics-community__value" style="color: rgb(54, 52, 64);">
                            <span class="chart-analytics-community__currency">₽</span>1.32K
                        </span>
                    </button>
                    
                    <button class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right">
                        <span class="chart-analytics-community__text" style="color: rgb(226, 64, 65);">
                            Подписки
                        </span>
                        
                        <span class="chart-analytics-community__value" style="color: rgb(226, 64, 65);">
                            <span class="chart-analytics-community__currency">₽</span>219
                        </span>
                    </button>
                    
                    <button class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right">
                        <span class="chart-analytics-community__text" style="color: rgb(255, 159, 67);">
                            Медиатовары
                        </span>
                        
                        <span class="chart-analytics-community__value" style="color: rgb(255, 159, 67);">
                            <span class="chart-analytics-community__currency">₽</span>2.32K
                        </span>
                    </button>
                </div>
                
                <div class="chart-analytics-community__chart">
                    <canvas id="chart"height="400"></canvas>
                </div>
                
                <div class="chart-analytics-community__footer">
                    <div class="chart-analytics-community__label">
                        <span class="chart-analytics-community__value">
                            <span class="chart-analytics-community__currency">₽</span>150.33K
                        </span>
                        
                        <span class="chart-analytics-community__text">
                            Всего заработано
                        </span>
                    </div>
                    
                    <div class="chart-analytics-community__label chart-analytics-community__label--right">
                        <span class="chart-analytics-community__value">
                            <span class="chart-analytics-community__currency">₽</span>1.15M
                        </span>
                        
                        <span class="chart-analytics-community__text">
                            Поступления  за период
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Таблица -->
            <div class="analytics-community__table">
                <div
                    id="table"
                    class="table-2 analytics-community-messages-table"
                ></div>
            </div>

            <div class="analytics-community__footer">
                <div class="export-data">
                    <h3 class="export-data__title">
                        Экспорт: 
                    </h3>
                    
                    <button type="button" class="button-empty button-empty--primary">
                        Excel
                    </button>
                    
                    <button type="button" class="button-empty button-empty--primary">
                        CSV
                    </button>
                </div>
                
                <!-- Pagination -->
                <div
                    id="pagination"
                    class="pagination analytics-community__pagination"
                ></div>
            </div>
        </div>
    </div>
@endsection
