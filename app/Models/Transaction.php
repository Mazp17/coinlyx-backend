<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'amount', 'type'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
