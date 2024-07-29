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
 * @property float $x
 * @property float $y
 * @property float $w
 * @property float $h
 * @property string $color
 * @property float $brightness
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $experiment_id
 * @property string|null $name
 * @property float|null $reaction_time
 * @property-read Experiment|null $experiment
 * @method static \Illuminate\Database\Eloquent\Builder|Figure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Figure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Figure query()
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereBrightness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereExperimentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereReactionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereW($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereY($value)
 * @property int|null $size_min
 * @property int|null $size_max
 * @property int|null $brightness_min
 * @property int|null $brightness_max
 * @property int|null $angle
 * @property int|null $angles
 * @property string|null $colors
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereAngle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereAngles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereBrightnessMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereBrightnessMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereColors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereSizeMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereSizeMin($value)
 * @property int $result_id
 * @property-read Experiment|null $result
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereResultId($value)
 * @property string|null $xx
 * @property string|null $yy
 * @method static \Database\Factories\FigureFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereXx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereYy($value)
 * @property string|null $hh
 * @property string|null $ww
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereHh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereWw($value)
 * @property string|null $x_click
 * @property string|null $y_click
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereXClick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereYClick($value)
 * @property int|null $x_oblast
 * @property int|null $y_oblast
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereXOblast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Figure whereYOblast($value)
 * @mixin \Eloquent
 */
class Figure extends Model
{
    use HasFactory;

    public const array COLORS = [
        '#05FF00' => 'Зеленый',
        '#FFF500' => 'Желтый',
        '#FF0000' => 'Красный',
        '#00F0FF' => 'Голубой',
        '#0500FF' => 'Синий',
        '#FA00FF' => 'Розовый',
        '#FFFFFF' => 'Белый',
        '#FF007A' => 'Бордовый',
        '#FF5C00' => 'Оранжевый'
    ];

    protected $fillable = [
        'xx',
        'yy',
        'ww',
        'hh',
        'x',
        'y',
        'w', 'x_oblast','y_oblast',
        'h','x_click', 'y_click',
        'color',
        'brightness',
        'experiment_id',
        'name',
        'reaction_time',
        'size_min',
        'size_max',
        'brightness_min',
        'brightness_max',
        'angle',
        'angles',
        'colors',
        'brightness',
        'show_time'
    ];

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }
}
