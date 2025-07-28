<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stockout';
    protected $primaryKey = 'stockoutid';

    protected $fillable = [
        'itemid',
        'volume',
        'keterangan',
        'recipient',
    ];

     public function item()
    {
        return $this->belongsTo(Item::class, 'itemid', 'itemid');
    }
}