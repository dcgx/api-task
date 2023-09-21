<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Task",
 *     title="Task",
 *     description="Task model",
 *     @OA\Property(property="title", type="string", example="Task Title"),
 *     @OA\Property(property="description", type="string", example="Task Description"),
 *     @OA\Property(property="status", type="boolean", example=true),
 * )
 */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::creating(function ($task) {
            $task->user_id = auth()->id();
        });
    }

    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
