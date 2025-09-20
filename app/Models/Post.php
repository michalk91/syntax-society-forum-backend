<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reply;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'title', 'content', 'is_approved'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
