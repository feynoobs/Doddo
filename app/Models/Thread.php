<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'board_id',
        'name',
        'sequence',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'board_id')->orderBy('sequence');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class)->orderBy('id');
    }
}