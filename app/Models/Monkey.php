<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @method static \Database\Factories\MonkeyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey query()
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $elvis_id
 * @property int $age
 * @property float $weight
 * @property string $comment
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey whereElvisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monkey whereWeight($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Experiment> $experiments
 * @property-read int|null $experiments_count
 * @mixin \Eloquent
 */
class Monkey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'elvis_id',
        'age',
        'weight',
        'comment',
    ];

    public function experiments(): HasMany
    {
        return $this->hasMany(Experiment::class);
    }

    public static function destroy($ids): int
    {
        $monkey = Monkey::query()->findOrFail($ids);
        $models = Experiment::whereMonkeyId($monkey->id)->each(function (Experiment $experiment) {
            FigureResult::whereExperimentId($experiment->id)->each(function (FigureResult $figureResult) {
                $figureResult->delete();
            });
            Figure::whereExperimentId($experiment->id)->each(function (Figure $figure) {
                $figure->delete();
            });
            var_dump($experiment->delete());
        });


        return parent::destroy($ids);
    }

    public function lastExperiment($number = null): Experiment
    {
        return $this->experiments()->where(compact('number'))->get()->last();
    }


}
