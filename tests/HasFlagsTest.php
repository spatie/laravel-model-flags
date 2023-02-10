<?php

use Carbon\Carbon;
use Spatie\ModelFlags\Models\Flag;
use Spatie\ModelFlags\Tests\TestSupport\TestClasses\TestModel;
use Spatie\TestTime\TestTime;

beforeEach(function () {
    $this->model = TestModel::create();

    $this->otherModel = TestModel::create();
});

it('can add a flag to a model', function () {
    expect($this->model->hasFlag('flag-a'))->toBeFalse();

    $this->model->flag('flag-a');

    expect($this->model->hasFlag('flag-a'))->toBeTrue();
    expect($this->model->hasFlag('flag-B'))->toBeFalse();
    expect($this->otherModel->hasFlag('flag-a'))->toBeFalse();
});

it('can unflag a model', function () {
    $this->model->unflag('flag-a');

    $this->model->flag('flag-a');
    $this->model->flag('flag-b');
    expect($this->model->hasFlag('flag-a'))->toBeTrue();

    $this->model->unflag('flag-a');

    expect($this->model->hasFlag('flag-a'))->toBeFalse();
    expect($this->model->hasFlag('flag-b'))->toBeTrue();
});

it('can get the flags from a model', function () {
    $this->model
        ->flag('flag-a')
        ->flag('flag-b');

    $flags = $this->model->flags;

    expect($flags)->toHaveCount(2);
    expect($flags[0]->name)->toBe('flag-a');
    expect($flags[1]->name)->toBe('flag-b');
});

it('can get the flag names from a model', function () {
    $this->model
        ->flag('flag-a')
        ->flag('flag-b');

    expect($this->model->flagNames())->toBe(['flag-a', 'flag-b']);
});

it('has a scope to get models with a certain flag', function () {
    expect(TestModel::flagged('flag-a')->get())->toHaveCount(0);

    $this->model->flag('flag-a');
    expect(TestModel::flagged('flag-a')->get())->toHaveCount(1);

    $this->otherModel->flag('flag-b');
    expect(TestModel::flagged('flag-a')->get())->toHaveCount(1);

    $this->otherModel->flag('flag-a');
    expect(TestModel::flagged('flag-a')->get())->toHaveCount(2);
});

it('has a scope to get models without a certain flag', function () {
    expect(TestModel::notFlagged('flag-a')->get())->toHaveCount(2);

    $this->model->flag('flag-a');
    expect(TestModel::notFlagged('flag-a')->get())->toHaveCount(1);

    $this->otherModel->flag('flag-b');
    expect(TestModel::notFlagged('flag-a')->get())->toHaveCount(1);

    $this->otherModel->flag('flag-a');
    expect(TestModel::notFlagged('flag-a')->get())->toHaveCount(0);
});

it('can_remove_flags_on_model_delete', function () {
    $this->model->flag('flag-a');

    $this->model->delete();

    $this->assertEquals(0, $this->model->flags()->get()->count());
});

it('can_remove_model_without_flags', function () {
    expect($this->model->delete())->toBeTrue();
});

it('can get the latest flag', function() {
    $this->model->flag('a');

    $this->model->flag('b');
    $this->model->flag('c');

    $flag = $this->model->latestFlag();
    expect($flag->name)->toBe('c');

    $flag = $this->model->latestFlag('b');
    expect($flag->name)->toBe('b');

    expect($this->model->latestFlag('non-existent'))->toBeNull();
});

it('can get the date of the latest flag', function() {
    TestTime::freeze();

    expect($this->model->lastFlaggedAt('a'))->toBeNull();

    $this->model->flag('a');
    expect($this->model->lastFlaggedAt('a'))->toBeInstanceOf(Carbon::class);

    TestTime::addDay();
    $date = now()->format('Y-m-d');
    $this->model->flag('a');

    expect($this->model->lastFlaggedAt('a')->format('Y-m-d'))->toBe($date);
});
