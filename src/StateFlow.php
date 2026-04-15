<?php

declare(strict_types = 1);

namespace Amondar\PhpStateFlow;

use BackedEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class State
 *
 * @author Amondar-SO
 */
class StateFlow
{
    protected static array $shortnamesCache = [];

    protected static ?string $shortnamesRegexCache = null;

    protected static array $stateByLabelCache = [];

    protected static array $labelByStateCache = [];

    /**
     * Get a list of state codes as an inline array.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary
     * @return array The array of state codes extracted from the cases.
     */
    public static function getShortnames(string $vocabulary = State::class): array
    {
        if (isset(static::$shortnamesCache[$vocabulary])) {
            return static::$shortnamesCache[$vocabulary];
        }

        return static::$shortnamesCache[$vocabulary] = array_map(
            static fn($case) => $case->value,
            $vocabulary::cases()
        );
    }

    /**
     * Generate a regex pattern for matching shortnames derived from the specified vocabulary.
     *
     * @param  string  $vocabulary  The class defining the shortnames, typically an enum or similar structure.
     * @return string A regex pattern representing the list of shortnames joined by the '|' character.
     */
    public static function getShortnamesRegex(string $vocabulary = State::class): string
    {
        if (static::$shortnamesRegexCache !== null) {
            return static::$shortnamesRegexCache;
        }

        $list = implode('|', static::getShortnames($vocabulary));

        return static::$shortnamesRegexCache = $list;
    }

    /**
     * Retrieve a mapping of normalized state labels to their corresponding values.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary  The enum class defining the states.
     * @return array<string, string> An associative array where keys are normalized state labels, and values are the corresponding enum values.
     */
    public static function getStateByLabelMap(string $vocabulary = State::class): array
    {
        if (isset(static::$stateByLabelCache[$vocabulary])) {
            return static::$stateByLabelCache[$vocabulary];
        }

        return static::$stateByLabelCache[$vocabulary] = Arr::mapWithKeys(
            $vocabulary::cases(),
            static fn(BackedEnum $case) => [Str::of($case->name)->lower()->squish()->snake()->toString() => $case->value]
        );
    }

    /**
     * Retrieve a mapping of state values to their corresponding human-readable labels.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary  The enum class defining the states.
     * @return array<string, string> An associative array where keys are lowercased state values
     *                               and values are the corresponding human-readable labels.
     */
    public static function getLabelByStateMap(string $vocabulary = State::class): array
    {
        if (isset(static::$labelByStateCache[$vocabulary])) {
            return static::$labelByStateCache[$vocabulary];
        }

        return static::$labelByStateCache[$vocabulary] = Arr::mapWithKeys(
            $vocabulary::cases(),
            static fn(BackedEnum $case) => [Str::lower($case->value) => Str::of($case->name)
                ->replace('_', ' ')
                ->title()
                ->toString()]
        );
    }

    /**
     * Get the short two-letter code by full state name string.
     *
     * @template T of BackedEnum
     *
     * @param  string  $fullName  Full state name (e.g., "New York").
     * @param  bool  $lower  If true, returns the result in lowercase.
     * @param  class-string<T>  $vocabulary
     * @return string|null The two-letter code (e.g., "NY") or null if not found.
     */
    public static function getLabel(string $fullName, bool $lower = false, string $vocabulary = State::class): ?string
    {
        $normalized = Str::of($fullName)->lower()->squish()->snake()->toString();

        $result = static::getStateByLabelMap($vocabulary)[$normalized] ?? null;

        if ($result === null) {
            return null;
        }

        return $lower ? Str::lower($result) : $result;
    }

    /**
     * Retrieve the name corresponding to a given short identifier within a specified vocabulary.
     *
     * @template T of BackedEnum
     *
     * @param  string  $short  The short identifier for which the corresponding name needs to be retrieved.
     * @param  bool  $lower  Determines whether the returned name should be in lowercase.
     * @param  class-string<T>  $vocabulary  The enum class defining the states to look up.
     * @return string|null The corresponding name if found, or null if no match exists.
     */
    public static function getName(string $short, bool $lower = false, string $vocabulary = State::class): ?string
    {
        $normalized = Str::lower($short);

        $result = static::getLabelByStateMap($vocabulary)[$normalized] ?? null;

        if ($result === null) {
            return null;
        }

        return $lower ? Str::lower($result) : $result;
    }

    /**
     * Generate a regex pattern to match city names followed by a state abbreviation, with optional default city inclusion.
     *
     * @template T of BackedEnum
     *
     * @param  int  $maxCityName  Maximum allowed length for the city name.
     * @param  string|null  $defaultCity  An optional default city to include explicitly in the regex.
     * @param  class-string<T>  $vocabulary  The enum class defining the states to look up.
     * @return string A regex pattern for matching city names combined with state abbreviations.
     */
    public static function getCityRegex(int $maxCityName = 30, ?string $defaultCity = null, string $vocabulary = State::class): string
    {
        $list = static::getShortnamesRegex($vocabulary);

        if ($defaultCity === null) {
            return "(?:(?:[\w\s-]{1,$maxCityName})\,\s?(?:$list))";
        }

        return "((?:(?:[\w\s-]{1,$maxCityName})\,\s?(?:$list))|($defaultCity))";
    }

    /**
     * Generate a regular expression for matching origin strings in a specific format.
     *
     * Allowed examples:
     *  - "New York, NY"
     *  - "NY"
     *
     * @param  int  $maxCityName  The maximum length allowed for city names in the regex.
     * @param  string  $vocabulary  The enum class defining the valid states.
     * @return string A regular expression string that matches origin patterns, including city and state combinations or single states.
     */
    public static function getOriginRegex(int $maxCityName = 30, string $vocabulary = State::class): string
    {
        $list = static::getShortnamesRegex($vocabulary);
        $cityRegex = static::getCityRegex($maxCityName, null, $vocabulary);

        return "($cityRegex|($list))";
    }
}
