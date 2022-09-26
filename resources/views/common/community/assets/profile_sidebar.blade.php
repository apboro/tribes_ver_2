<div
    class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0 @if(data::get('is_visible_sidebar') == 'false') invisible @endif"
    data-tab="profileBlock"
>
    <!-- Project Card -->
    <div class="card community-profile-card">
        <div class="card-body">
            <div class="user-avatar-section">
                <div class="d-flex align-items-center flex-column">
                    <!-- Аватар -->
                    <img class="img-fluid rounded mb-1"
                        src="@if ($community->image !== null) {{ $community->image }} @else/images/no-image.svg @endif"
                        height="auto" width="100%" alt="User avatar"
                    />

                    <!-- Мессенджер -->
                    <div class="user-info text-center">
                        <h4 id="community_name">
                            {{ $community->title }}
                        </h4>

                        @if ($community->isTelegram())
                            <span class="badge badge-glow bg-info">Telegram</span>
                        @else
                            <span class="badge badge-glow bg-primary">Discord</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column justify-content-around my-2 pt-75">
                <!-- Владелец аккаунта -->
                <div class="d-flex align-items-start me-2">
                    <span class="badge bg-light-primary p-75 rounded">
                        <i data-feather="user" class="font-medium-2"></i>
                    </span>

                    <div class="ms-75">
                        <h4
                            class="mb-0 truncate truncate__profile-name"
                            title="{{ Auth::user()->telegramData()->publicName() }}"
                        >
                            {{ Auth::user()->telegramData()->publicName() }}
                        </h4>

                        <small>
                            {{ __('community.account_owner') }}
                        </small>
                    </div>
                </div>

                <!-- Баланс -->
                <div class="d-flex align-items-start mt-1">
                    <span class="badge bg-light-success p-75 rounded">
                        <i data-feather='credit-card' class="font-medium-2"></i>
                    </span>

                    <div class="ms-75">
                        <h4 class="mb-0" id="balance_title">
                            {{ $community->balance }}
                        </h4>

                        <small>
                            {{ __('base.balance') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- О сообществе -->
            <h4 class="fw-bolder border-bottom pb-50 mb-1">
                {{ __('community.about_community') }}
            </h4>
            
            <div class="info-container mb-1">
                <ul class="list-unstyled mb-0">
                    <li class="mb-75">
                        <span class="fw-bolder me-25">
                            {{ __('community.date_of_connection') }}:
                        </span>

                        <span class="badge bg-light-info" id="create_date_title">
                            {{ $community->created_at->format('m-d-y') }}
                        </span>
                    </li>

                    <li class="mb-75">
                        <span class="fw-bolder me-25">
                            {{ __('community.paid_subscribers_label') }}:
                        </span>

                        <span class="badge bg-light-info">
                            {{ $community->statistic ? $community->statistic->repository()->getPaidSubscribers() : null }} / {{ $community->statistic ? $community->statistic->repository()->getAllSubscribers() : null }}
                        </span>
                    </li>

                    <li class="mb-75">
                        <span class="fw-bolder me-25">
                            {{ __('community.unique_visitors') }}:
                        </span>

                        <span class="badge bg-light-info" id="unique_visitors_title">
                            {{ $community->statistic ? $community->statistic->repository()->getHosts() : null }}
                        </span>
                    </li>

                    <li class="mb-75">
                        <span class="fw-bolder me-25">
                            {{ __('community.subscription_renewers') }}:
                        </span>

                        <span class="badge bg-light-info" id="renewed_subscription_title">
                            {{ $community->statistic ? $community->statistic->repository()->getProlongationUser() : null }}
                        </span>
                    </li>

                    <li class="mb-75">
                        <span class="fw-bolder me-25">
                            {{ __('community.payment_page_views') }}:
                        </span>

                        <span class="badge bg-light-info" id="payment_page_views">
                            {{ $community->statistic ? $community->statistic->repository()->getViews() :null }}
                        </span>
                    </li>

                    <li class="mb-75">
                        <span class="fw-bolder me-25">
                            {{ __('community.amount_of_donations') }}:
                        </span>

                        <span class="badge bg-light-info" id="donation_amounts_title">
                            {{ $community->statistic ? $community->statistic->repository()->getDonateSum() :null }}
                        </span>
                    </li>

                    <li class="mb-75">
                        <span class="fw-bolder me-25">
                            {{ __('community.number_of_donations') }}:
                        </span>

                        <span class="badge bg-light-info" id="donation_count_title">
                            {{ $community->statistic ? $community->statistic->repository()->getTotalDonate() :null }}
                        </span>
                    </li>

                    <li class="mb-75">
                        <span class="fw-bolder me-25">
                            {{ __('community.amount_of_tariffs') }}:
                        </span>

                        <span class="badge bg-light-info" id="tariff_amounts_title">
                            {{ $community->statistic ? $community->statistic->repository()->getTariffSum() :null }}
                        </span>
                    </li>

                    <li class="">
                        <span class="fw-bolder me-25">
                            {{ __('community.number_of_tariffs') }}:
                        </span>

                        <span class="badge bg-light-info" id="tariff_count_title">
                            {{ $community->statistic ? $community->statistic->repository()->getTotalTariff() :null }}
                        </span>
                    </li>
                </ul>
            </div>

            @if ($community->tariff->variants->first() !== NULL)
                <!-- Ссылки -->
            <h4 class="fw-bolder border-bottom pb-50 mb-1">
                {{ __('base.links') }}
            </h4>

            <div class="info-container">
                <ul class="list-unstyled mb-0">
                    <li class="">
                        <!-- Payment page -->
                        <span class="col-md-12 col-lg-8 col-xl-9 col-12">
                            <div role="alert" aria-live="polite" aria-atomic="true"
                                class="alert alert-success mb-0 mt-1 mt-lg-0">
                                <div class="alert-body">
                                    <span class="fw-bolder me-25">
                                        {{ __('community.link_payment_for_access_to_community') }}:
                                    </span>
                                    <strong>
                                        <a href="{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}" target="_blank">перейти</a>
                                    </strong> |
                                    <strong>
                                        <a class="text-primary pointer"
                                            onclick="copyText('{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}')">
                                            {{ __('base.copy') }}
                                        </a>
                                    </strong>
                                </div>
                            </div>
                        </span>
                    </li>
                </ul>
            </div>
            @endif
            
        </div>
    </div>
</div>
