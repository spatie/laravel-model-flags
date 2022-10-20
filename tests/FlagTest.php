<?php

use Spatie\ModelFlags\Models\Flag;
use Spatie\ModelFlags\Tests\TestSupport\TestClasses\TestModel;

beforeEach(function () {
    $this->model = TestModel::create();

    $this->otherModel = TestModel::create();
});

it('has a relation back to the flaggable', function() {
    $this->model->flag('flag-a');

    $flag = Flag::first();

    expect($flag->flaggable->id)->toBe($this->model->id);
});
