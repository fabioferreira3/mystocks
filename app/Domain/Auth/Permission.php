<?php

namespace Domain\Auth\Permission;

use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Traits\UuidTrait;

class Permission extends SpatiePermission
{
    use UuidTrait;

    protected $guarded = ['id'];
    protected $keyType = 'string';
}
