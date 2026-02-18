<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $resident_id
 * @property int $user_id
 * @property int|null $block_id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $address
 * @property string $contact_number_1
 * @property string|null $contact_number_2
 * @property Carbon $birthdate
 * @property string|null $occupation
 * @property bool $is_pwd
 * @property string|null $profile_photo
 * @property-read string $full_name
 * @property-read int $age
 * @property-read User $user
 * @property-read Block|null $block
 * @property-read \Illuminate\Database\Eloquent\Collection $complaints
 * @property-read \Illuminate\Database\Eloquent\Collection $certificateRequests
 */
class Resident extends Model
{
    protected $fillable = [
        'resident_id', 'user_id', 'block_id', 'first_name', 'middle_name',
        'last_name', 'address', 'contact_number_1', 'contact_number_2',
        'birthdate', 'occupation', 'is_pwd', 'profile_photo',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'is_pwd' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function certificateRequests(): HasMany
    {
        return $this->hasMany(CertificateRequest::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birthdate)->age;
    }

    public function isSenior(): bool
    {
        return Carbon::parse($this->birthdate)->age >= 60;
    }
}