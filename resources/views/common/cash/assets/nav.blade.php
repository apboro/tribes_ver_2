<ul class="nav nav-tabs" role="tablist">
    <!-- Tab btn 1 -->
    <li class="nav-item">
        <a
            class="nav-link {{ request()->is('*payments/card*') ? 'active' : ''  }}"    
            href="{{ route('payment.card.list') }}"
            aria-controls="accounts-and-cards"
        >
            {{ __('payment.accounts_and_cards') }}
        </a>
    </li>

    <!-- Tab btn 2 -->
    <li class="nav-item">
        <a
            class="nav-link {{ request()->is('*payments/income*') ? 'active' : ''  }}"
            href="{{ route('payment.income.list') }}"
            aria-controls="income"
            
        >
            {{ __('payment.income') }}
        </a>
    </li>

    <!-- Tab btn 3 -->
    <li class="nav-item">
        <a
            class="nav-link {{ request()->is('*payments/outcome*') ? 'active' : ''  }}"
            href="{{ route('payment.outcome.list') }}"
            aria-controls="outcome"
        >
            {{ __('payment.payout') }}
        </a>
    </li>
</ul>
