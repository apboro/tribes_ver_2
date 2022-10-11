<div class="col-sm-4 col-md-6 col-lg-6">
    <div class="card community-item">
        <div class="card-body">
            <h4 class="card-title community-item__title" title="{{ $donate->title }}">
                {{ $donate->title }}
            </h4>
            <p>Сумма платежей: {{ array_sum($donate->getSumDonateByIndex()) }}₽</p>
            <p>Кол-во платежей: {{ count($donate->getSumDonateByIndex()) }}</p>
            <p>Индекс: {{ $donate->index }}</p>
            <div role="alert" aria-live="polite" aria-atomic="true" class="alert alert-success mb-0 mt-1 mt-lg-0">
                <div class="alert-body">
                    <i data-feather="alert-circle" class="font-medium-1"></i>
                    {{ __('donate.inline_command') }} 
                    <strong>
                        {{ '@' . env('TELEGRAM_BOT_NAME') }} {{ $donate ? $donate->inline_link : __('donate.created_on_save') }}
                    </strong> — 
                    <span
                        class="text-primary pointer"
                        onclick="copyText('{{ '@' . env('TELEGRAM_BOT_NAME') }} {{ $donate ? $donate->inline_link : 'Создастся при сохранении' }}')"
                    >
                        {{ __('base.copy') }}
                    </span>
                </div>
            </div>
            
            <div class="card-footer d-flex justify-content-center p-1">
                <a
                    href="{{ route('community.donate.add', ['community' => $donate->community_id, 'id' => $donate->id]) }}"
                    class="btn btn-flat-dark waves-effect text-nowrap">
                    {{ __('base.edit') }}
                </a>
            <a
                type="submit"
                href="{{ route('community.donate.remove', ['community' => $donate->community_id, 'id' => $donate->id]) }}"
                class="btn btn-flat-danger waves-effect text-nowrap">
                {{ __('base.remove') }}
            </a>
            </div>
        </div>
    </div>
</div>
