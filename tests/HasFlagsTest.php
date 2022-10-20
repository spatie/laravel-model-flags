<?php

use Spatie\ModelFlags\Models\Flag;
use Spatie\ModelFlags\Tests\TestSupport\TestClasses\TestModel;

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

