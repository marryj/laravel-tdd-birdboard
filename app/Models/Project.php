<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    public $guarded = [];

    public function path()
    {
        return "/projects/$this->id";
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
