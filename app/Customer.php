<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['namacustomer', 'alamat', 'email', 'telepon'];

    protected $hidden = ['created_at', 'updated_at'];
}
