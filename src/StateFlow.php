<?php

declare(strict_types = 1);

namespace Amondar\PhpStateFlow;

use BackedEnum;

/**
 * Class State
 *
 * @author Amondar-SO
 */
class StateFlow
{
    protected static array $abbreviationsCache = [];

    protected static ?string $abbreviationsRegexCache = null;

    protected static array $namesCache = [];

    protected static array $namesRegexCache = [];

    protected static array $abbreviationByNameCache = [];

    protected static array $nameByAbbreviationCache = [];

    /**
     * Get a list of state codes as an inline array.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary
     * @return array The array of state codes extracted from the cases.
     */
    public static function getAbbreviations(string $vocabulary = State::class): array
    {
        if (isset(static::$abbreviationsCache[ $vocabulary ])) {
            return static::$abbreviationsCache[ $vocabulary ];
        }

        return static::$abbreviationsCache[ $vocabulary ] = array_map(
            static fn($case) => $case->name,
            $vocabulary::cases()
        );
    }

    /**
     * Retrieves the count of abbreviations for the given vocabulary.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary
     * @return int The total number of abbreviations found for the specified vocabulary.
     */
    public static function getCount(string $vocabulary = State::class): int
    {
        return count(static::getAbbreviations($vocabulary));
    }

    /**
     * Retrieve a list of names from the given enumeration class.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary  The enum class from which to extract the names.
     * @return array<int, string> An array where each value corresponds to the name of an enum case.
     */
    public static function getNames(string $vocabulary = State::class): array
    {
        if (isset(static::$namesCache[ $vocabulary ])) {
            return static::$namesCache[ $vocabulary ];
        }

        return static::$namesCache[ $vocabulary ] = array_map(
            static fn($case) => $case->value,
            $vocabulary::cases()
        );
    }

    /**
     * Generate a regex pattern for matching abbreviations derived from the specified vocabulary.
     *
     * @param  string  $vocabulary  The class defining the abbreviations, typically an enum or similar structure.
     * @return string A regex pattern representing the list of abbreviations joined by the '|' character.
     */
    public static function getAbbreviationsRegex(string $vocabulary = State::class): string
    {
        if (static::$abbreviationsRegexCache !== null) {
            return static::$abbreviationsRegexCache;
        }

        $list = implode('|', static::getAbbreviations($vocabulary));

        return static::$abbreviationsRegexCache = $list;
    }

    /**
     * Generate a regex pattern matching all normalized names from the specified vocabulary.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary  The enum class defining the names.
     * @return string A regex pattern string that matches any of the normalized names.
     */
    public static function getNamesRegex(string $vocabulary = State::class): string
    {
        if (isset(static::$namesRegexCache[ $vocabulary ])) {
            return static::$namesRegexCache[ $vocabulary ];
        }

        $list = implode('|', static::getNames($vocabulary));

        return static::$namesRegexCache[ $vocabulary ] = $list;
    }

    /**
     * Retrieve a mapping of normalized state names to their corresponding values.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary  The enum class defining the states.
     * @return array<string, string> An associative array where keys are normalized state names, and values are the
     *                               corresponding enum values.
     */
    public static function getAbbreviationByNameMap(string $vocabulary = State::class): array
    {
        if (isset(static::$abbreviationByNameCache[ $vocabulary ])) {
            return static::$abbreviationByNameCache[ $vocabulary ];
        }

        $keys = [];
        $values = [];

        foreach ($vocabulary::cases() as $case) {
            $keys[] = static::normalizeNameKey($case->value);
            $values[] = $case->name;
        }

        return static::$abbreviationByNameCache[ $vocabulary ] = array_combine($keys, $values);
    }

    /**
     * Retrieve a mapping of state values to their corresponding human-readable names.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary  The enum class defining the states.
     * @return array<string, string> An associative array where keys are lowercased state values
     *                               and values are the corresponding human-readable names.
     */
    public static function getNameByAbbreviationMap(string $vocabulary = State::class): array
    {
        if (isset(static::$nameByAbbreviationCache[ $vocabulary ])) {
            return static::$nameByAbbreviationCache[ $vocabulary ];
        }

        $keys = [];
        $values = [];

        foreach ($vocabulary::cases() as $case) {
            $keys[] = mb_strtolower($case->name);
            $values[] = $case->value;
        }

        return static::$nameByAbbreviationCache[ $vocabulary ] = array_combine($keys, $values);
    }

    /**
     * Get the short two-letter code by full state name string.
     *
     * @template T of BackedEnum
     *
     * @param  string  $name  Full state name (e.g., "New York").
     * @param  bool  $lower  If true, returns the result in lowercase.
     * @param  class-string<T>  $vocabulary
     * @return string|null The two-letter code (e.g., "NY") or null if not found.
     */
    public static function getAbbreviation(string $name, bool $lower = false, string $vocabulary = State::class): ?string
    {
        $normalized = static::normalizeNameKey($name);

        $result = static::getAbbreviationByNameMap($vocabulary)[ $normalized ] ?? null;

        if ($result === null) {
            return null;
        }

        return $lower ? mb_strtolower($result) : $result;
    }

    /**
     * Retrieve the name corresponding to a given short identifier within a specified vocabulary.
     *
     * @template T of BackedEnum
     *
     * @param  string  $abbreviation  The short identifier for which the corresponding name needs to be retrieved.
     * @param  bool  $lower  Determines whether the returned name should be in lowercase.
     * @param  class-string<T>  $vocabulary  The enum class defining the states to look up.
     * @return string|null The corresponding name if found, or null if no match exists.
     */
    public static function getName(string $abbreviation, bool $lower = false, string $vocabulary = State::class): ?string
    {
        $normalized = mb_strtolower($abbreviation);

        $result = static::getNameByAbbreviationMap($vocabulary)[ $normalized ] ?? null;

        if ($result === null) {
            return null;
        }

        return $lower ? mb_strtolower($result) : $result;
    }

    /**
     * Generate a regex pattern to match city names followed by a state abbreviation, with optional default city
     * inclusion.
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
        $abbreviationsList = static::getAbbreviationsRegex($vocabulary);
        $namesList = static::getNamesRegex($vocabulary);

        if ($defaultCity === null) {
            return "(?:(?:[\w\s-]{1,$maxCityName})\,\s?(?:$abbreviationsList|$namesList))";
        }

        return "((?:(?:[\w\s-]{1,$maxCityName})\,\s?(?:$abbreviationsList|$namesList))|($defaultCity))";
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
     * @return string A regular expression string that matches origin patterns, including city and state combinations
     *                or single states.
     */
    public static function getOriginRegex(int $maxCityName = 30, string $vocabulary = State::class): string
    {
        $abbreviationsList = static::getAbbreviationsRegex($vocabulary);
        $namesList = static::getNamesRegex($vocabulary);
        $cityRegex = static::getCityRegex($maxCityName, null, $vocabulary);

        return "($cityRegex|$abbreviationsList|$namesList)";
    }

    /**
     * Performs a search within a specified vocabulary based on a given query string.
     *
     * @template T of BackedEnum
     *
     * @param  string  $query  The string to search for, which will be normalized to lowercase and trimmed.
     * @param  class-string<T>  $vocabulary  The enum class defining the states to look up.
     * @return array An array of matching results where the query is found in the vocabulary.
     */
    public static function search(string $query, string $vocabulary = State::class): array
    {
        $query = mb_strtolower(mb_trim($query));

        if ($query === '') {
            return [];
        }

        return array_filter(
            static::getNameByAbbreviationMap($vocabulary),
            static fn(string $name, string $key) => str_contains(mb_strtolower($name), $query) || $key === $query,
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Retrieves a random item from specified vocabulary of states.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $vocabulary  The enum class containing the vocabulary to select from.
     * @return array{abbreviation: string, name: string} An associative array with 'abbreviation' as the key for the random abbreviation and 'name' as the key for its corresponding name.
     */
    public static function getRandom(string $vocabulary = State::class): array
    {
        $list = static::getNameByAbbreviationMap($vocabulary);
        $abbreviation = array_rand(static::getNameByAbbreviationMap($vocabulary));

        return ['abbreviation' => mb_strtoupper($abbreviation), 'name' => $list[$abbreviation]];
    }

    /**
     * Normalizes a given name key by converting it to lowercase, replacing specific characters, and trimming excess spaces.
     *
     * @param  string  $name  The input name key to be normalized.
     * @return string|null The normalized name key, or null if the input cannot be processed.
     */
    public static function normalizeNameKey(string $name): ?string
    {
        // Squish multiple spaces into a single space, and remove any combining spaces.
        $result = preg_replace('~(\s|\x{3164}|\x{1160})+~u', ' ', mb_trim($name));

        return mb_strtolower(str_replace([' ', '-'], '_', $result));
    }
}
