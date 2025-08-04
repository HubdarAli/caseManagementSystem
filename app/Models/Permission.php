<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Support\Arr;


class Permission extends SpatiePermission
{
    use HasFactory;

    public $incrementing  = true;

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }
    
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_has_permissions', 'permission_id', 'group_id');
    }

    public function subMenus()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
