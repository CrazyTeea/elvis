<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @property int $experiment_id
 * @property int $monkey_id
 * @method static \Illuminate\Database\Eloquent\Builder|File whereExperimentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereMonkeyId($value)
 * @property-read \App\Models\Experiment|null $experiment
 * @method static \Database\Factories\FileFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'experiment_id', 'monkey_id'];

    public function experiment()
    {
        return $this->belongsTo(Experiment::class);
    }
}
