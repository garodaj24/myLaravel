<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\AuthUserScope;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "completed_at",
        "user_id"
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new AuthUserScope);
    }
}
