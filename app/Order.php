<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'table_id', 'name', 'user_id', 'status_id'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function status()
    {
    	return $this->belongsTo('App\Status');
    }
}
