<?php

namespace App\Models;

use Bican\Roles\Models\Role as BicanRole;

class Role extends BicanRole
{
    protected $fillable = ['company_id', 'slug','name', 'level'];

	const ROLE_SUPER_ADMIN = 'superadmin';
	const ROLE_ADMIN = 'admin';
	const ROLE_OPERATOR = 'operator';
    const ROLE_AGENT = 'agent';
    const ROLE_METHODIST = 'methodist';
	const ROLE_MEDIATOR = 'mediator';

	const ROLES = [
		self::ROLE_OPERATOR,
		self::ROLE_ADMIN,
		self::ROLE_SUPER_ADMIN,
        self::ROLE_AGENT,
        self::ROLE_METHODIST,
		self::ROLE_MEDIATOR,
	];


	//relationships

    public function company(){

        return $this->belongsTo(Company::class);
    }

}
