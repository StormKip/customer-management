<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    /**
     * Polymorphic relationship, descends from User.
     * @return array
     */
    public function user()
    {
        return $this->morphOne(\App\Models\User::class, 'userable');
    }

    /**
     * Model type.
     * @return string
     */
    public function type()
    {
        return 'Customer';
    }
}
