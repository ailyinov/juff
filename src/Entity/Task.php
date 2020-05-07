<?php

namespace Juff\Entity;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $timestamps = false;
    protected $table = 'task';
    protected $fillable = ['user_email', 'user_name', 'description', 'is_completed'];
}