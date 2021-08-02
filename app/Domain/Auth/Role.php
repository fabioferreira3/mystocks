<?php

namespace Domain\Auth;

use App\Traits\UuidTrait;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use UuidTrait;

    protected $guarded = ['id'];
    protected $keyType = 'string';
}
