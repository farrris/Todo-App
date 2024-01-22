<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     title="Task",
 *     description="Задача",
 *     @OA\Xml(
 *         name="Task"
 *     )
 * )
 */

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "user_id",
        "status"
    ];

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var bigInteger
     */
    private $id;
    /**
     * @OA\Property(
     *      title="title",
     *      description="Заголовок задачи",
     *      example="test title"
     * )
     *
     * @var string
     */
    private $title;
    /**
     * @OA\Property(
     *      title="description",
     *      description="Описание задачи",
     *      example="test description"
     * )
     *
     * @var string
     */
    private $description;
    /**
     * @OA\Property(
     *      title="status",
     *      description="Статус задачи",
     *      example="in progress"
     * )
     *
     * @var string
     */
    private $status;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
