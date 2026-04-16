# amondar-libs/php-state-flow

A lightweight PHP package for working with U.S. state codes and names.

It provides:
- Fast in-memory lookups for state code/name conversions
- Prebuilt regex fragments for validation/parsing
- Optional support for custom vocabularies via backed enums

## Requirements

- PHP `^8.3`
- `illuminate/support` `10.*|11.*|12.*|13.*`

## Installation

```bash
composer require amondar-libs/php-state-flow
```

## Quick Start

```php
<?php

use Amondar\PhpStateFlow\StateFlow;

StateFlow::getLabel('New York');   // "NY"
StateFlow::getName('ny');          // "New York"

// Regex fragments
$cityRegex = StateFlow::getCityRegex();
preg_match('/^' . $cityRegex . '$/', 'Los Angeles, CA'); // 1
```

## Core Concepts

### Default Vocabulary

By default, all methods use `Amondar\PhpStateFlow\State`, which contains 48 U.S. states.

### Custom Vocabulary

Most methods accept a third argument (`$vocabulary`) with a class-string of a backed enum.
This allows reuse of the same helpers with your own enum dataset.

## API Reference

### `getShortnames(string $vocabulary = State::class): array`

Returns all enum backed values (state codes by default), e.g. `['AL', 'AZ', ..., 'WY']`.

```php
$codes = StateFlow::getShortnames();
```

### `getLabels(string $vocabulary = State::class): array`

Returns normalized enum case names in title case, e.g. `['Alabama', 'New York', ...]`.

```php
$labels = StateFlow::getLabels();
```

### `getShortnamesRegex(string $vocabulary = State::class): string`

Returns a pipe-separated regex fragment of codes, e.g. `AL|AZ|AR|...`.

```php
$codeRegex = StateFlow::getShortnamesRegex();
```

### `getLabelsRegex(string $vocabulary = State::class): string`

Returns a pipe-separated regex fragment of normalized labels, e.g. `Alabama|Arizona|...`.

```php
$labelRegex = StateFlow::getLabelsRegex();
```

### `getStateByLabelMap(string $vocabulary = State::class): array`

Returns a map of normalized snake-case labels to enum values.

Examples:
- `new_york => NY`
- `west_virginia => WV`

```php
$map = StateFlow::getStateByLabelMap();
```

### `getLabelByStateMap(string $vocabulary = State::class): array`

Returns a map of lowercase enum values to human-readable labels.

Examples:
- `ny => New York`
- `al => Alabama`

```php
$map = StateFlow::getLabelByStateMap();
```

### `getLabel(string $fullName, bool $lower = false, string $vocabulary = State::class): ?string`

Converts a full label to its short code.

Behavior:
- Input is normalized (`lower` + `squish` + `snake`)
- Returns `null` if label is unknown
- Optional lowercase output

```php
StateFlow::getLabel('New York');         // "NY"
StateFlow::getLabel('  New   York  ');   // "NY"
StateFlow::getLabel('New York', true);   // "ny"
StateFlow::getLabel('NY');               // null
```

### `getName(string $short, bool $lower = false, string $vocabulary = State::class): ?string`

Converts a short code to full state name.

Behavior:
- Code lookup is case-insensitive
- Returns `null` if code is unknown
- Optional lowercase output

```php
StateFlow::getName('NY');        // "New York"
StateFlow::getName('ny');        // "New York"
StateFlow::getName('NY', true);  // "new york"
StateFlow::getName('XX');        // null
```

### `getCityRegex(int $maxCityName = 30, ?string $defaultCity = null, string $vocabulary = State::class): string`

Builds a regex fragment for values like:
- `City, ST` (code)
- `City, State Name` (label)

Supports:
- Optional whitespace after comma
- Configurable max city length (`$maxCityName`)
- Optional exact fallback city (`$defaultCity`)

```php
$regex = StateFlow::getCityRegex();
preg_match('/^' . $regex . '$/', 'New York, NY');       // 1
preg_match('/^' . $regex . '$/', 'New York, New York'); // 1
preg_match('/^' . $regex . '$/', 'Invalid City, XX');   // 0

$regexWithDefault = StateFlow::getCityRegex(30, 'Anywhere');
preg_match('/^' . $regexWithDefault . '$/', 'Anywhere'); // 1
```

### `getOriginRegex(int $maxCityName = 30, string $vocabulary = State::class): string`

Builds a regex fragment that matches:
- `City, ST`
- `City, State Name`
- Standalone state code
- Standalone state label

```php
$regex = StateFlow::getOriginRegex();

preg_match('/^' . $regex . '$/', 'NY');            // 1
preg_match('/^' . $regex . '$/', 'New York, NY');  // 1
preg_match('/^' . $regex . '$/', 'XX');            // 0
```

## Using a Custom Enum Vocabulary

```php
<?php

enum Region: string
{
    case NORTH = 'N';
    case SOUTH = 'S';
}

use Amondar\PhpStateFlow\StateFlow;

$codes = StateFlow::getShortnames(Region::class);   // ['N', 'S']
$labels = StateFlow::getLabels(Region::class);      // ['North', 'South']
$name = StateFlow::getName('n', false, Region::class); // 'North'
```

## Caching Notes

This package caches computed arrays/regex strings in static in-memory properties for the current PHP process.

- Improves repeated lookup performance.
- Cache lifetime is process-bound (request/worker lifecycle).
- Fully compatible with long-lived process applications like Laravel Octane, Roadrunner, etc.

## Testing

Run tests with Pest:

```bash
./vendor/bin/pest
```

Or run parallel tests via composer script:

```bash
composer run ppest
```

## License

MIT. See `LICENSE.md`.
