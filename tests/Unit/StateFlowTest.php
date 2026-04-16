<?php

declare(strict_types = 1);

use Amondar\PhpStateFlow\StateFlow;

it('getAbbreviations returns all state codes', function () {
    $shortnames = StateFlow::getAbbreviations();

    expect($shortnames)->toBeArray()
        ->and($shortnames)->toContain('AL', 'NY', 'WY')
        ->and(count($shortnames))->toBe(48);
});

it('getAbbreviationsRegex returns a regex string of state codes', function () {
    $regex = StateFlow::getAbbreviationsRegex();

    expect($regex)->toBeString()
        ->and($regex)->toContain('AL|AZ|AR');
});

it('getNames returns all state codes', function () {
    $shortnames = StateFlow::getNames();

    expect($shortnames)->toBeArray()
        ->and($shortnames)->toContain('Alabama', 'New York')
        ->and(count($shortnames))->toBe(48);
});

it('getNamesRegex returns a regex string of state codes', function () {
    $regex = StateFlow::getNamesRegex();

    expect($regex)->toBeString()
        ->and($regex)->toContain('|New York|', 'Alabama|');
});

it('getAbbreviationByNameMap returns a mapping of labels to values', function () {
    $map = StateFlow::getAbbreviationByNameMap();

    expect($map)->toBeArray()
        ->and($map)->toHaveKey('new_york', 'NY')
        ->and($map)->toHaveKey('alabama', 'AL')
        ->and($map)->toHaveKey('west_virginia', 'WV');
});

it('getNameByAbbreviationMap returns a mapping of values to headlines', function () {
    $map = StateFlow::getNameByAbbreviationMap();

    expect($map)->toBeArray()
        ->and($map)->toHaveKey('ny', 'New York')
        ->and($map)->toHaveKey('al', 'Alabama')
        ->and($map)->toHaveKey('wv', 'West Virginia');
});

it('getAbbreviation returns the short code by full name', function () {
    expect(StateFlow::getAbbreviation('New York'))->toBe('NY')
        ->and(StateFlow::getAbbreviation('new york'))->toBe('NY')
        ->and(StateFlow::getAbbreviation('  New   York  '))->toBe('NY')
        ->and(StateFlow::getAbbreviation('NY'))->toBeNull()
        ->and(StateFlow::getAbbreviation('Non Existent'))->toBeNull();
});

it('getAbbreviation can return lowercase result', function () {
    expect(StateFlow::getAbbreviation('New York', true))->toBe('ny');
});

it('getName returns the full name by short code', function () {
    expect(StateFlow::getName('NY'))->toBe('New York')
        ->and(StateFlow::getName('ny'))->toBe('New York')
        ->and(StateFlow::getName('AL'))->toBe('Alabama')
        ->and(StateFlow::getName('Unknown'))->toBeNull();
});

it('getName can return lowercase result', function () {
    expect(StateFlow::getName('NY', true))->toBe('new york');
});

it('getCityRegex returns a valid regex pattern for city and state', function () {
    $regex = StateFlow::getCityRegex();

    expect($regex)->toBeString()
        ->and('New York, NY')->toMatch('/^' . $regex . '$/')
        ->and('New York, New York')->toMatch('/^' . $regex . '$/')
        ->and('Los Angeles, CA')->toMatch('/^' . $regex . '$/')
        ->and('Los Angeles,CA')->toMatch('/^' . $regex . '$/')
        ->and('Invalid City, XX')->not->toMatch('/^' . $regex . '$/');
});

it('getCityRegex can include a default city', function () {
    $regex = StateFlow::getCityRegex(30, 'Anywhere');

    expect('Anywhere')->toMatch('/^' . $regex . '$/')
        ->and('New York, NY')->toMatch('/^' . $regex . '$/');
});

it('getOriginRegex returns a regex pattern for city/state or just state', function () {
    $regex = StateFlow::getOriginRegex();

    expect('New York, NY')->toMatch('/' . $regex . '/')
        ->and('New York,New York')->toMatch('/' . $regex . '/')
        ->and('NY')->toMatch('/^' . $regex . '$/')
        ->and('California,CA')->toMatch('/' . $regex . '/')
        ->and('XX')->not->toMatch('/^' . $regex . '$/');
});

it('search returns matching states by abbreviation fragment', function () {
    $result = StateFlow::search('n');

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('ny', 'New York')
        ->and($result)->toHaveKey('nc', 'North Carolina')
        ->and($result)->toHaveKey('tn', 'Tennessee')
        ->and($result)->not->toHaveKey('al');
});

it('search normalizes query and returns empty array for empty input', function () {
    expect(StateFlow::search('  NY  '))->toBe([
        'ny' => 'New York',
    ])
        ->and(StateFlow::search('   '))->toBe([]);
});

it('getRandom returns abbreviation and name pair from vocabulary map', function () {
    $random = StateFlow::getRandom();
    $map = StateFlow::getNameByAbbreviationMap();

    expect($random)->toBeArray()
        ->and($random)->toHaveKeys(['abbreviation', 'name'])
        ->and($random['abbreviation'])->toBeString()
        ->and($random['name'])->toBeString()
        ->and($map)->toHaveKey(mb_strtolower($random['abbreviation']), $random['name']);
});
