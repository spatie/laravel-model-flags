<?php

namespace Spatie\ModelFlags\Models\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use function Illuminate\Support\enum_value;
use Spatie\ModelFlags\Models\Flag;

/** @mixin Model */
trait HasFlags
{
    public static function bootHasFlags()
    {
        static::deleted(function (Model $deletedModel) {
            $deletedModel->flags()->delete();
        });
    }

    protected function enumValue(string|BackedEnum $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        return (string) $value->value;
    }

    public function flags(): MorphMany
    {
        return $this->morphMany(config('model-flags.flag_model'), 'flaggable');
    }

    public function hasFlag(string|BackedEnum $name): bool
    {
        return $this
            ->flags()
            ->where('name', $this->enumValue($name))
            ->exists();
    }

    public function flag(string|BackedEnum $name): self
    {
        $this->flags()->firstOrCreate(['name' => $this->enumValue($name)])->touch();

        return $this;
    }

    public function unflag(string|BackedEnum $name): self
    {
        $this->flags()->where('name', $this->enumValue($name))->delete();

        return $this;
    }

    public function scopeFlagged(Builder $query, string|BackedEnum $name): void
    {
        $query
            ->whereHas(
                'flags',
                fn (Builder $query) => $query->where('name', $this->enumValue($name))
            );
    }

    public function scopeNotFlagged(Builder $query, string|BackedEnum $name): void
    {
        $query
            ->doesntHave(
                'flags',
                callback: fn (Builder $query) => $query->where('name', $this->enumValue($name))
            );
    }

    /**
     * @return array<int, string>
     */
    public function flagNames(): array
    {
        return $this->flags
            ->map(fn (Flag $flag) => $flag->name)
            ->toArray();
    }

    public function latestFlag(string|BackedEnum $name = null): ?Flag
    {
        return $this
            ->flags()
            ->when($name, fn (Builder $query) => $query->where('name', $this->enumValue($name)))
            ->orderByDesc('updated_at')->orderByDesc('id')
            ->first();
    }

    public function lastFlaggedAt(string|BackedEnum $name): ?Carbon
    {
        return $this->latestFlag($this->enumValue($name))?->updated_at;
    }
}
