<?php

namespace App\Models;

use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'address',
        'status',
        'plan',
    ];

    protected function casts(): array
    {
        return [
            'status' => RecordStatus::class,
        ];
    }

    public function features(): HasMany
    {
        return $this->hasMany(OrganizationFeature::class);
    }

    public function hasFeature(string $key): bool
    {
        // If plan is gold or premium, allow features by default unless explicitly disabled in organization_features
        $feature = $this->features()->where('feature_key', $key)->first();
        if ($feature) {
            return (bool) $feature->enabled;
        }

        return in_array($this->plan, ['gold', 'premium']);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function leadSources(): HasMany
    {
        return $this->hasMany(LeadSource::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function pipelineStages(): HasMany
    {
        return $this->hasMany(PipelineStage::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }
}
