<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        "storage_uuid",
        "original_file_name",
        "user_id"
    ];

    protected $table = "users_media";

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
