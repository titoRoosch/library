<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'publish_date'
    ];

    public function authors() {
        return $this->belongsToMany(Author::class, 'book_author_relations', 'book_id', 'author_id');
    }
}
