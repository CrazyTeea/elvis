<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $experiment_id
 * @property string $data
 * @method static \Database\Factories\TimeLineFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLine whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLine whereExperimentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLine whereId($value)
 * @property-read mixed $parsed_data
 * @mixin \Eloquent
 */
class TimeLine extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'experiment_id',
        'data'
    ];



    public function getParsedDataAttribute()
    {
        return json_decode($this->data, true);
    }
}
