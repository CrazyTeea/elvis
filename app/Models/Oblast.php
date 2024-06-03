<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $experiment_id
 * @property int|null $br_min
 * @property int|null $br_max
 * @property int|null $x1
 * @property int|null $x2
 * @property int|null $y1
 * @property int|null $y2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast query()
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereBrMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereBrMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereExperimentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereX1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereX2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereY1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oblast whereY2($value)
 * @mixin \Eloquent
 */
class Oblast extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'br_min',
        'br_max',
        'x1',
        'x2',
        'y1',
        'y2'
    ];
}
