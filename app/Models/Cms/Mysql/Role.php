<?php

namespace App\Models\Cms\Mysql;

use Spatie\Permission\Models\Role as SpatieRole; 
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Role extends SpatieRole
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d F Y H:i:s');
    }
}