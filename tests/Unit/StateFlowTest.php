<?php

declare(strict_types = 1);

use Amondar\PhpStateFlow\StateFlow;

test('getAbbreviations returns all state codes', function () {
    $shortnames = StateFlow::getAbbreviations();

    expect($shortnames)->toBeArray()
        ->and($shortnames)->toContain('AL', 'NY', 'WY')
        ->and(count($shortnames))->toBe(48);
});

test('getAbbreviationsRegex returns a regex string of state codes', function () {
    $regex = StateFlow::getAbbreviationsRegex();

    expect($regex)->toBeString()
        ->and($regex)->toContain('AL|AZ|AR');
});

test('getNames returns all state codes', function () {
    $shortnames = StateFlow::getNames();

    expect($shortnames)->toBeArray()
        ->and($shortnames)->toContain('Alabama', 'New York')
        ->and(count($shortnames))->toBe(48);
});

test('getNamesRegex returns a regex string of state codes', function () {
    $regex = StateFlow::getNamesRegex();

    expect($regex)->toBeString()
        ->and($regex)->toContain('|New York|', 'Alabama|');
});

test('getAbbreviationByNameMap returns a mapping of labels to values', function () {
    $map = StateFlow::getAbbreviationByNameMap();

    expect($map)->toBeArray()
        ->and($map)->toHaveKey('new_york', 'NY')
        ->and($map)->toHaveKey('alabama', 'AL')
        ->and($map)->toHaveKey('west_virginia', 'WV');
});

test('getNameByAbbreviationMap returns a mapping of values to headlines', function () {
    $map = StateFlow::getNameByAbbreviationMap();

    expect($map)->toBeArray()
        ->and($map)->toHaveKey('ny', 'New York')
        ->and($map)->toHaveKey('al', 'Alabama')
        ->and($map)->toHaveKey('wv', 'West Virginia');
});

test('getAbbreviation returns the short code by full name', function () {
    expect(StateFlow::getAbbreviation('New York'))->toBe('NY')
        ->and(StateFlow::getAbbreviation('new york'))->toBe('NY')
        ->and(StateFlow::getAbbreviation('  New   York  '))->toBe('NY')
        ->and(StateFlow::getAbbreviation('NY'))->toBeNull()
        ->and(StateFlow::getAbbreviation('Non Existent'))->toBeNull();
});

test('getAbbreviation can return lowercase result', function () {
    expect(StateFlow::getAbbreviation('New York', true))->toBe('ny');
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
