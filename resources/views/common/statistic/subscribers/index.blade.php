@extends('common.community.profile')

@section('tab')
    <div
        class="analytics-community community__tab"
        data-tab="analyticsSubscribersPage"
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
                    onchange="CommunityPage.analyticsSubscribersPage.switchTab(event)"
                >
                    <option value="{{ route('community.statistic.subscribers', $community) }}" selected>Подписчики</option>
                    <option value="{{ route('community.statistic.messages', $community) }}">Сообщения</option>
                    <option value="{{ route('community.statistic.payments', $community) }}">Финансы</option>
                </select>
                
                <div class="filter-analytics-community analytics-community__filter">
                    <select
                        class="select-rounded"
                        onchange="CommunityPage.analyticsSubscribersPage.switchFilter(event)"
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
                    <div class="chart-analytics-community__label chart-analytics-community__label--right">
                        <span class="chart-analytics-community__text">
                            Всего подписчиков
                        </span>
                        
                        <span class="chart-analytics-community__value">
                            2.36K
                        </span>
                    </div>
                    
                    <button class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right">
                        <span class="chart-analytics-community__text">
                            Покинули
                        </span>
                        
                        <span class="chart-analytics-community__value" style="color: rgb(226, 64, 65);">
                            -19
                        </span>
                    </button>
                    
                    <button class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right">
                        <span class="chart-analytics-community__text">
                            Вступили
                        </span>
                        
                        <span class="chart-analytics-community__value" style="color: rgb(33, 193, 105);">
                            +96
                        </span>
                    </button>
                </div>
                
                <div class="chart-analytics-community__chart">
                    <canvas id="messages_chart" height="400"></canvas>
                </div>
            </div>
            
            <div class="analytics-community__table">
                <div
                    id="subscribers_table"
                    class="table-2 analytics-community-subscribers-table"
                >
                    <div class="table__header">
                        <div class="table__header-item table__header-item--sortable">
                            <span>Имя подписчика</span>
                            
                            <button
                                class="table__sort-btn"

                            >
                                <i class="icon button-text__icon">
                                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="white" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                        
                        <div class="table__header-item table__header-item--sortable">
                            <span>Никнейм</span>
                            
                            <button class="table__sort-btn">
                                <i class="icon button-text__icon">
                                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="white" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                        
                        <div class="table__header-item table__header-item--sortable">
                            <span>Дата</span>
                            
                            <button class="table__sort-btn">
                                <i class="icon button-text__icon">
                                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="white" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                        
                        <div class="table__header-item table__header-item--sortable">
                            <span>Сообщения</span>
                            
                            <button class="table__sort-btn">
                                <i class="icon button-text__icon">
                                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="white" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                        
                        <div class="table__header-item table__header-item--sortable">
                            <span>Реакции (оставил)</span>
                            <button class="table__sort-btn">
                                <i class="icon button-text__icon">
                                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="white" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                        
                        <div class="table__header-item table__header-item--sortable">
                            <span>Реакции (получил)</span>
                            
                            <button class="table__sort-btn">
                                <i class="icon button-text__icon">
                                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="white" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                        
                        <div class="table__header-item table__header-item--sortable">
                            <span>Полезность</span>
                            
                            <button class="table__sort-btn">
                                <i class="icon button-text__icon">
                                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="white" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="table__body">
                        <div class="table__row-wrapper">
                            <div class="table__row">
                                <div>
                                    <div class="table__item table__item--changable">
                                        <a href="#" class="link">
                                            name text
                                        </a>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="table__item table__item--changable">
                                        <a href="#" class="link">
                                            username text
                                        </a>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="table__item table__item--changable">
                                        <span>
                                            08.09.2022
                                        </span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="table__item table__item--changable">
                                        messages text
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="table__item table__item--changable">
                                        reaction out text
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="table__item table__item--changable">
                                        reaction in text
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="table__item table__item--changable">
                                        utility text
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
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
                
                <div class="pagination analytics-community__pagination">
                    <div class="pagination__item pagination__description">
                        <span>
                            Кол-во отображаемых строк:
                        </span>
                        
                        <div class="select pagination__select select--bottom">
                            <div class="select__head">
                                <span class="select__value">
                                    15
                                </span>
                                
                                <i class="icon select__arrow">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.53317 5.53317C3.6665 5.39984 3.79984 5.33317 3.99984 5.33317C4.19984 5.33317 4.33317 5.39984 4.4665 5.53317L7.99984 9.0665L11.5332 5.53317C11.7998 5.2665 12.1998 5.2665 12.4665 5.53317C12.7332 5.79984 12.7332 6.19984 12.4665 6.4665L8.4665 10.4665C8.19984 10.7332 7.79984 10.7332 7.53317 10.4665L3.53317 6.4665C3.2665 6.19984 3.2665 5.79984 3.53317 5.53317Z" fill="#4C4957" class="icon__fill"></path></svg>
                                </i>
                            </div>
                            
                            <div class="select__body">
                                <div class="select__option selected">
                                    15
                                </div>
                                
                                <div class="select__option">
                                    30
                                </div>
                                
                                <div class="select__option">
                                    45
                                </div>
                            </div>
                        </div>
                        <span> из 16</span>
                    </div>
                    
                    <div class="pagination__item">
                        <div class="pagination__control">
                            <button class="button-text button-text--primary button-text--only-icon button-text--disabled">
                                <i class="icon">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.4668 3.53317C10.6002 3.6665 10.6668 3.79984 10.6668 3.99984C10.6668 4.19984 10.6002 4.33317 10.4668 4.4665L6.9335 7.99984L10.4668 11.5332C10.7335 11.7998 10.7335 12.1998 10.4668 12.4665C10.2002 12.7332 9.80016 12.7332 9.5335 12.4665L5.5335 8.4665C5.26683 8.19984 5.26683 7.79984 5.5335 7.53317L9.5335 3.53317C9.80016 3.2665 10.2002 3.2665 10.4668 3.53317Z" fill="#4C4957" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                        
                        <div class="pagination__control active">
                            <button class="pagination__page">
                                1
                            </button>
                        </div>
                        
                        <div class="pagination__control">
                            <button class="pagination__page">
                                2
                            </button>
                        </div>
                        
                        <div class="pagination__control">
                            <button class="button-text button-text--primary button-text--only-icon">
                                <i class="icon">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.53317 3.53317C5.39984 3.6665 5.33317 3.79984 5.33317 3.99984C5.33317 4.19984 5.39984 4.33317 5.53317 4.4665L9.0665 7.99984L5.53317 11.5332C5.2665 11.7998 5.2665 12.1998 5.53317 12.4665C5.79984 12.7332 6.19984 12.7332 6.4665 12.4665L10.4665 8.4665C10.7332 8.19984 10.7332 7.79984 10.4665 7.53317L6.4665 3.53317C6.19984 3.2665 5.79984 3.2665 5.53317 3.53317Z" fill="#4C4957" class="icon__fill"></path></svg>
                                </i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
