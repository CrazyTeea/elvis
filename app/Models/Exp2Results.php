<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $experiment_id
 * @property int|null $stimul_id
 * @property int|null $helper_id
 * @property int|null $position_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\Exp2ResultsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results query()
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereExperimentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereHelperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereStimulId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereUpdatedAt($value)
 * @property int|null $reaction
 * @property int|null $x
 * @property int|null $y
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereReaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exp2Results whereY($value)
 * @mixin \Eloquent
 */
class Exp2Results extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'stimul_id',
        'helper_id',
        'position_id',
        'x', 'y', 'reaction'
    ];
}
