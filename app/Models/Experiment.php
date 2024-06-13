<?php

namespace App\Models;

use Database\Factories\ExperimentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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
 * @property-read \App\Models\Oblast|null $oblast
 * @property-read Collection $position_strings
 * @property-read Collection<int, \App\Models\Position> $positions
 * @property-read int|null $positions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers> $helpers
 * @property-read int|null $helpers_count
 * @property-read \App\Models\Helpers|null $stimul
 * @property-read \App\Models\TimeLine|null $timeLine
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stimul> $stimuls
 * @property-read int|null $stimuls_count
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

    public function oblast(): HasOne
    {
        return $this->hasOne(Oblast::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function helpers(): HasMany
    {
        return $this->hasMany(Helpers::class);
    }

    public function stimuls(): HasMany
    {
        return $this->hasMany(Stimul::class);
    }

    public function timeLine(): HasOne
    {
        return $this->hasOne(TimeLine::class);
    }

    public function getPositionStringsAttribute(): Collection
    {
        return $this->positions->map(function (Position $position) {
            return $position->name;
        });
    }
}
