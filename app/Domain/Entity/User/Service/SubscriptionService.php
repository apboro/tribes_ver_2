<?php
namespace App\Domain\Entity\User\Service;

use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;

class SubscriptionService
{
    private const TRIAL_PERIOD = 1;
    private const PAID_PERIOD = 2;

    /**
     * User
     *
     * @var User $user
     */
    private User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function setTrialPeriod()
    {
        UserSubscription::setPerioud($this->user->id, self::TRIAL_PERIOD);
    }

    public function setPaidPeriod()
    {
        UserSubscription::setPerioud($this->user->id, self::PAID_PERIOD);
    }

    public function isExpiredDate(User $user): bool
    {
        return UserSubscription::checkPeriod($user->id);
    }

}