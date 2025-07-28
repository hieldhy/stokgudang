<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stockin';
    protected $primaryKey = 'stockinid';

    protected $fillable = [
        'itemid',
        'volume',
        'keterangan',
    ];

     public function item()
    {
        return $this->belongsTo(Item::class, 'itemid', 'itemid');
    }
}