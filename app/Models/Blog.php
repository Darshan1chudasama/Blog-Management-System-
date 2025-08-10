<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'user_id'];

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function likeCount()
    {
        return $this->likes()->count();
    }
}
