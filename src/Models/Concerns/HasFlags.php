<?php

namespace Spatie\ModelFlags\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\ModelFlags\Models\Flag;

/** @mixin \Illuminate\Database\Eloquent\Model */
trait HasFlags
{
    public function hasFlag(string $name): bool
    {
        return $this
            ->flags()
            ->where('name', $name)
            ->exists();
    }

    public function flag($name): self
    {
        $this->flags()->firstOrCreate(['name' => $name]);

        return $this;
    }

    public function scopeFlagged(Builder $query, string $name): void
    {
        $query
            ->whereHasMorph(
                'flags',
                $this->getMorphClass(),
                fn (Builder $query) => $query->where('name', $name)
            );
    }

    public function flags(): MorphMany
    {
        return $this->morphMany(Flag::class, 'flaggable');
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
}
