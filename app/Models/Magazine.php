<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Magazine extends Model
{
    use HasFactory;

    protected $table = 'magazines';
    protected $guarded = [];

    public function authors() {
        return $this->belongsToMany(Author::class, 'author_magazine');
    }
}
