<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $guarded = [];
}
