<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Event",
 *     description="Событие",
 *     @OA\Xml(
 *         name="Event"
 *     )
 * )
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "date"
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
     *      title="date",
     *      description="Дата события",
     *      example="2024-01-22 17:33:39"
     * )
     *
     * @var string
     */
    private $date;
}
