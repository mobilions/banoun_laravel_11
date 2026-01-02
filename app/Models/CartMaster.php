<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Cartmaster extends Model
{
    protected $table = "cartmasters";
    
    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'subtotal',
        'discount',
        'delivery',
        'grandtotal',
        'totalqty',
        'paymenttype',
        'paymentstatus',
        'orderstatus',
        'comments',
        'is_checkouted',
        'created_by',
        'updated_by',
        'referenceId',
        'TranID',
        'is_giftwrap',
        'giftwrap_price',
        'PaymentID',
        'TrackID',
        'orderstatus',
        
    ];

    protected $casts = [
        'user_id' => 'integer',
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'delivery' => 'decimal:2',
        'grandtotal' => 'decimal:2',
        'totalqty' => 'integer',
        'orderstatus' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'master_id');
    }

    public function orderLogs()
    {
        return $this->hasMany(OrderLog::class, 'cartmaster_id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(Orderstatus::class, 'orderstatus');
    }
}

