<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TronTransaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * 可填充字段
     */
    public $fillable = [
        'id',
        'status',
        'block_ts',
        'from_address',
        'from_address_tag',
        'to_address',
        'to_address_tag',
        'block',
        'quant',
        'confirmed',
        'contractRet',
        'finalResult',
        'revert',
        'contract_type',
        'contract_address',
        'fromAddressIsContract',
        'toAddressIsContract',
        'riskTransaction',
    ];

    /**
     * 类型转换
     */
    protected $casts = [
        'from_address_tag'              =>  'array',
        'to_address_tag'                =>  'array',
    ];
}
