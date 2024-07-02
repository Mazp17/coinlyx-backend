<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['account_id', 'amount', 'type'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the options for activity logging.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['account_id', 'amount', 'type'])
            ->logOnlyDirty()
            ->useLogName('transaction');
    }
}
