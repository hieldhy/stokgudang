<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $primaryKey = 'itemid';

    protected $fillable = [
        'nama_perangkat',
        'type',
        'spesifikasi',
        'volume',
        'satuan',
        'serialnumber',
        'keterangan',
        'referensi',
    ];

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class, 'itemid', 'itemid');
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class, 'itemid', 'itemid');
    }
}