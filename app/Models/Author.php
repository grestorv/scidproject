<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';
    protected $guarded = [];

    public function magazines()
    {
        return $this->belongsToMany(Magazine::class, 'author_magazine');
    }
}
