<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionOfPathologyTest extends Model
{
    protected $fillable = [
        'bill_id',
        'test_id',
        'amount',
        'tax'
    ];

    public function bill()
    {
        return $this->belongsTo(PathologyBill::class, 'bill_id');
    }

    public function test()
    {
        return $this->belongsTo(PathologyTest::class, 'test_id');
    }
}
