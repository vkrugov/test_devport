<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property string $url
 * @property int $user_id
 * @property string $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\History> $histories
 * @property-read int|null $histories_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUserId($value)
 * @mixin \Eloquent
 */
class Game extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    public const DAYS_TO_EXPIRED = 7;

    /**
     * @var string[]
     */
    protected $fillable = [
        'url',
        'user_id',
        'expired_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'user_id'    => 'int',
        'expired_at' => 'datetime:Y',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    /**
     * @return string
     */
    public function getKeyName(): string
    {
        return "url";
    }
}
