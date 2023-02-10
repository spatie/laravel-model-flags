<?php

namespace Spatie\ModelFlags\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
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

    public function flags(): MorphMany
    {
        return $this->morphMany(config('model-flags.flag_model'), 'flaggable');
    }

    public function hasFlag(string $name): bool
    {
        return $this
            ->flags()
            ->where('name', $name)
            ->exists();
    }

    public function flag($name): self
    {
        $this->flags()->firstOrCreate(['name' => $name])->touch();

        return $this;
    }

    public function unflag(string $name): self
    {
        $this->flags()->where('name', $name)->delete();

        return $this;
    }

    public function scopeFlagged(Builder $query, string $name): void
    {
        $query
            ->whereHas(
                'flags',
                fn (Builder $query) => $query->where('name', $name)
            );
    }

    public function scopeNotFlagged(Builder $query, string $name): void
    {
        $query
            ->doesntHave(
                'flags',
                callback: fn (Builder $query) => $query->where('name', $name)
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

    public function latestFlag(string $name = null): ?Flag
    {
        return $this
            ->flags()
            ->when($name, fn(Builder $query) => $query->where('name', $name))
            ->orderByDesc('updated_at')->orderByDesc('id')
            ->first();
    }

    public function lastFlaggedAt(string $name): ?Carbon
    {
       return $this->latestFlag($name)?->updated_at;
    }
}
