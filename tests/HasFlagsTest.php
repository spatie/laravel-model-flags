<?php

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

it('can get the flag names from a model', function() {
    $this->model
        ->flag('flag-a')
        ->flag('flag-b');

    expect($this->model->flagNames())->toBe(['flag-a', 'flag-b']);
});
