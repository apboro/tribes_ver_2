<?php

namespace App\Events;

use App\Models\Course;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuyCourse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Course $course
     */
    public Course $course;

    /**
     * @var User $user
     */

    public User $user;

    /**
     * Create a new event instance.
     *
     * @param Course $course
     * @param User $user
     */

    public function __construct(Course $course,User $user)
    {
        //
        $this->course = $course;
        $this->user = $user;
    }

}
