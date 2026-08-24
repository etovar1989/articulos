<?php

namespace App\Models;

use DB\SQL;
use DB\SQL\Mapper;

class Tag extends Mapper
{
    public function __construct(SQL $db)
    {
        parent::__construct($db, 'tags');
    }
}
