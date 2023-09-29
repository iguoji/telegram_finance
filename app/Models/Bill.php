<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 明细记录
     */
    public function details()
    {
        return $this->hasMany(BillDetail::class, 'bill_id', 'id')->withTrashed();
    }
}
