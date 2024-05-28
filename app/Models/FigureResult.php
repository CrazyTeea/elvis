<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property int $id
 * @property int $figure_id
 * @property int $experiment_id
 * @property float $reaction_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult[] whereExperimentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult[] whereFigureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereReactionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereUpdatedAt($value)
 * @property-read Experiment $experiment
 * @property-read Figure $figure
 * @property string|null $x
 * @property string|null $y
 * @method static \Database\Factories\FigureResultFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereY($value)
 * @property string|null $color
 * @property string|null $x_click
 * @property string|null $y_click
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereXClick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereYClick($value)
 * @property int|null $h
 * @property int|null $w
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereW($value)
 * @property int|null $x_oblast
 * @property int|null $y_oblast
 * @property int|null $b
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereXOblast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FigureResult whereYOblast($value)
 * @mixin \Eloquent
 */
class FigureResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'figure_id',
        'experiment_id','x_oblast','y_oblast',
        'x', 'y', 'color', 'w', 'h',
        'x_click', 'y_click',
        'reaction_time'
    ];

    public function figure(): BelongsTo
    {
        return $this->belongsTo(Figure::class);
    }

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }
}
