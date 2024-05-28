<?php

namespace App\Models;

use Database\Factories\ExperimentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $monkey_id
 * @property int $number
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Figure> $figures
 * @property-read int|null $figures_count
 * @property-read Monkey $monkey
 * @method static ExperimentFactory factory($count = null, $state = [])
 * @method static Builder|Experiment newModelQuery()
 * @method static Builder|Experiment newQuery()
 * @method static Builder|Experiment query()
 * @method static Builder|Experiment whereCreatedAt($value)
 * @method static Builder|Experiment whereId($value)
 * @method static Builder|Experiment whereMonkeyId($value)
 * @method static Builder|Experiment whereName($value)
 * @method static Builder|Experiment whereNumber($value)
 * @method static Builder|Experiment whereUpdatedAt($value)
 * @property-read Collection<int, FigureResult> $figureResults
 * @property-read int|null $figure_results_count
 * @property int|null $br_min
 * @property int|null $br_max
 * @property int|null $x1
 * @property int|null $x2
 * @property int|null $y1
 * @property int|null $y2
 * @method static Builder|Experiment whereBrMax($value)
 * @method static Builder|Experiment whereBrMin($value)
 * @method static Builder|Experiment whereX1($value)
 * @method static Builder|Experiment whereX2($value)
 * @method static Builder|Experiment whereY1($value)
 * @method static Builder|Experiment whereY2($value)
 * @mixin Eloquent
 */
class Experiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'monkey_id',
        'number',
        'name',
        'br_min',
        'br_max',
        'x1',
        'x2',
        'y1',
        'y2',
    ];

    public function monkey(): BelongsTo
    {
        return $this->belongsTo(Monkey::class);
    }

    public function figures(): HasMany
    {
        return $this->hasMany(Figure::class);
    }

    public function figureResults(): HasMany
    {
        return $this->hasMany(FigureResult::class);
    }
}
