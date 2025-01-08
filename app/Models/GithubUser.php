<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GithubUser extends Model
{
    protected $fillable = ['name','login','company','followers','public_repos','average_followers'];
}
