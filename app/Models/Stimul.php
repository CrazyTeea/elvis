<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 *
 *
 * @property int $id
 * @property int $experiment_id
 * @property string $name
 * @property int|null $length
 * @property int|null $frequency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Experiment|null $experiment
 * @method static \Database\Factories\StimulFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul whereExperimentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stimul whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stimul extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'name',
        'length',
        'frequency'
    ];

    public function experiment(): HasOne
    {
        return $this->hasOne(Experiment::class);
    }
}
