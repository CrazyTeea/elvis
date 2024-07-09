<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $experiment_id
 * @property string $name
 * @property int|null $br
 * @property int|null $thickness
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\HelpersFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers query()
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers whereBr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers whereExperimentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers whereThickness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Helpers whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Helpers extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'name',
        'br',
        'thickness',
        'offset'
    ];
}
