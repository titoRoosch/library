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

    public function rents()
    {
        return $this->belongsToMany(Rent::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'rents', 'book_id', 'user_id')->withPivot('rent_date', 'scheduled_return', 'status');
    }
}
