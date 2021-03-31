<?php

namespace App\Models;

use App\Models\Traits\UuidPrimaryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Post extends Model
{
    use HasFactory, UuidPrimaryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'main_image',
        'owner',
    ];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Create UUID for id.
     */
    protected static function booted()
    {
        static::created(
            function ($user)
            {
                $user->id = Uuid::uuid4()->toString();
            }
        );
    }

    /**
     * Attach main image file to post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function main_image()
    {
        return $this->hasOne(File::class);
    }

    /**
     * Attach owner (user) to post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Attach tags to post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
