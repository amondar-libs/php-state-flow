<?php

declare(strict_types = 1);

use Amondar\PhpStateFlow\StateFlow;

test('getShortnames returns all state codes', function () {
    $shortnames = StateFlow::getShortnames();

    expect($shortnames)->toBeArray()
        ->and($shortnames)->toContain('AL', 'NY', 'WY')
        ->and(count($shortnames))->toBe(48);
});

test('getShortnamesRegex returns a regex string of state codes', function () {
    $regex = StateFlow::getShortnamesRegex();

    expect($regex)->toBeString()
        ->and($regex)->toContain('AL|AZ|AR');
});

test('getLabels returns all state codes', function () {
    $shortnames = StateFlow::getLabels();

    expect($shortnames)->toBeArray()
        ->and($shortnames)->toContain('Alabama', 'New York')
        ->and(count($shortnames))->toBe(48);
});

test('getLabelsRegex returns a regex string of state codes', function () {
    $regex = StateFlow::getLabelsRegex();

    expect($regex)->toBeString()
        ->and($regex)->toContain('|New York|', 'Alabama|');
});

test('getStateByLabelMap returns a mapping of labels to values', function () {
    $map = StateFlow::getStateByLabelMap();

    expect($map)->toBeArray()
        ->and($map)->toHaveKey('new_york', 'NY')
        ->and($map)->toHaveKey('alabama', 'AL')
        ->and($map)->toHaveKey('west_virginia', 'WV');
});

test('getLabelByStateMap returns a mapping of values to headlines', function () {
    $map = StateFlow::getLabelByStateMap();

    expect($map)->toBeArray()
        ->and($map)->toHaveKey('ny', 'New York')
        ->and($map)->toHaveKey('al', 'Alabama')
        ->and($map)->toHaveKey('wv', 'West Virginia');
});

test('getLabel returns the short code by full name', function () {
    expect(StateFlow::getLabel('New York'))->toBe('NY')
        ->and(StateFlow::getLabel('new york'))->toBe('NY')
        ->and(StateFlow::getLabel('  New   York  '))->toBe('NY')
        ->and(StateFlow::getLabel('NY'))->toBeNull()
        ->and(StateFlow::getLabel('Non Existent'))->toBeNull();
});

test('getLabel can return lowercase result', function () {
    expect(StateFlow::getLabel('New York', true))->toBe('ny');
});

test('getName returns the full name by short code', function () {
    expect(StateFlow::getName('NY'))->toBe('New York')
        ->and(StateFlow::getName('ny'))->toBe('New York')
        ->and(StateFlow::getName('AL'))->toBe('Alabama')
        ->and(StateFlow::getName('Unknown'))->toBeNull();
});

test('getName can return lowercase result', function () {
    expect(StateFlow::getName('NY', true))->toBe('new york');
});

test('getCityRegex returns a valid regex pattern for city and state', function () {
    $regex = StateFlow::getCityRegex();

    expect($regex)->toBeString()
        ->and('New York, NY')->toMatch('/^' . $regex . '$/')
        ->and('New York, New York')->toMatch('/^' . $regex . '$/')
        ->and('Los Angeles, CA')->toMatch('/^' . $regex . '$/')
        ->and('Los Angeles,CA')->toMatch('/^' . $regex . '$/')
        ->and('Invalid City, XX')->not->toMatch('/^' . $regex . '$/');
});

test('getCityRegex can include a default city', function () {
    $regex = StateFlow::getCityRegex(30, 'Anywhere');

    expect('Anywhere')->toMatch('/^' . $regex . '$/')
        ->and('New York, NY')->toMatch('/^' . $regex . '$/');
});

test('getOriginRegex returns a regex pattern for city/state or just state', function () {
    $regex = StateFlow::getOriginRegex();

    expect('New York, NY')->toMatch('/' . $regex . '/')
        ->and('New York,New York')->toMatch('/' . $regex . '/')
        ->and('NY')->toMatch('/^' . $regex . '$/')
        ->and('California,CA')->toMatch('/' . $regex . '/')
        ->and('XX')->not->toMatch('/^' . $regex . '$/');
});
