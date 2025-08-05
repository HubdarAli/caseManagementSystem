<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function exportColumnPermissions()
    {
        # code...
        return $this->hasMany(ExportColumnPermissions::class);
    }


    public function findPermissions(array $params = [])
    {
        return $this->findByParam($params);
    }
}
