<?php

namespace App\Models;

use App\Models\Curriculum\CourseEnrolment;
use App\Models\Curriculum\CourseReview;
use App\Models\Curriculum\QuizScore;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class OrganizerUser extends Authenticatable
{
  use HasFactory;
}
