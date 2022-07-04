<?php

namespace App\Repositories\Follower;

interface FollowerRepositoryContract
{
    public function getAllCourses();
    public function getFollowerCourse($hash);
    public function getLesson($request, $course);
    public function getTemplate($lesson);
}