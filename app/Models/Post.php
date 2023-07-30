<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Psy\TabCompletion\Matcher\FunctionsMatcher;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id', 'title', 'news_content', 'image'
    ];


    //relasi writer terhadap tabel user
    public Function writer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    //relasi comments terhadap tabel post
    /**
     * Get all of the comments for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
