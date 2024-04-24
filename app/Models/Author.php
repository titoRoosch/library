<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date'
    ];

    public function books() {
        return $this->belongsToMany(Book::class, 'book_author_relations', 'author_id', 'book_id');
    }
}
