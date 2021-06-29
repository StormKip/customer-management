<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{

    protected $table = 'admins';

    /**
     * Polymorphic relationship, descends from User.
     * @return array
     */
    public function user()
    {
        return $this->morphOne(\App\Models\Admin::class, 'userable');
    }

    /**
     * Model type.
     * @return string
     */
    public function type()
    {
        return 'Admin';
    }

}
