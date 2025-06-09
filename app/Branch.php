<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Branch extends Model
{
    /**
     * @var string
     */
    protected $table = 'branches';

    /**
     * @var array
     */
    protected $guarded = [];
}
