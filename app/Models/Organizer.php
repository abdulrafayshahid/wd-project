<?php

namespace App\Models;

use App\Models\Event\Booking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Organizer extends Model implements AuthenticatableContract
{
  use HasFactory, Authenticatable;
  protected $fillable = [
    'photo',
    'email',
    'phone',
    'username',
    'password',
    'company_or_individual',
    'commercial_no',
    'vat_no',
    'status',
    'amount',
    'facebook',
    'twitter',
    'linkedin',
    'role_id',
    'created_by',
    'email_verified_at',
  ];

  //withdraw
  public function withdraws()
  {
    return $this->hasMany(Withdraw::class);
  }

  //organizer info
  public function organizer_info()
  {
    return $this->hasOne(OrganizerInfo::class);
  }
}
