<?php
/**
 * StringMethods
 *
 * Methods to manage strings
 */

class String
{
  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// CREATE  /////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Create a string from a number
   *
   * @param  integer $count A number
   * @param  string  $many  If many
   * @param  string  $one   If one
   * @param  string  $zero  If one
   * @return string         A string
   */
  public static function accord($count, $many, $one, $zero = null)
  {
    if($count == 1) $output = $one;
    else if($count == 0 and !empty($zero)) $output = $zero;
    else $output = $many;

    return sprintf($output, $count);
  }

  /**
   * Generates a random suite of words
   *
   * @param integer  $words  The number of words
   * @param integer  $length The length of each word
   *
   * @return string
   */
  public static function randomStrings($words, $length = 10)
  {
    return String::from('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
      ->shuffle()
      ->split($length)
      ->slice(0, $words)
      ->implode(' ')
      ->obtain();
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// ANALYZE /////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Get a String's length
   *
   * @param string $string
   *
   * @return integer
   */
  public static function length($string)
  {
    return mb_strlen($string);
  }

  ////////////////////////////////////////////////////////////////////
  ///////////////////////////// FETCH FROM ///////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Find one or more needles in one or more haystacks
   *
   * @param  array|string $string        The haystack(s) to search in
   * @param  array|string $needle        The needle(s) to search for
   * @param  boolean      $caseSensitive Whether the function is case sensitive or not
   * @param  boolean      $absolute      Whether all needle need to be found or whether one is enough
   * @return boolean      Found or not
   */
  public static function find($string, $needle, $caseSensitive = false, $absolute = false)
  {
    // If several needles
    if (is_array($needle) or is_array($string)) {

      if (is_array($needle)) {
        $sliceFrom = $needle;
        $sliceTo   = $string;
      } else {
        $sliceFrom = $string;
        $sliceTo   = $needle;
      }

      $found = 0;
      foreach ($sliceFrom as $need) {
        if(static::find($sliceTo, $need, $absolute, $caseSensitive)) $found++;
      }

      return ($absolute) ? count($sliceFrom) == $found : $found > 0;
    }

    // If not case sensitive
    if (!$caseSensitive) {
      $string = strtolower($string);
      $needle = strtolower($needle);
    }

    // If string found
    $pos = strpos($string, $needle);

    return !($pos === false);
  }

  /**
   * Slice a string with another string
   */
  public static function slice($string, $slice)
  {
    $sliceTo   = static::sliceTo($string, $slice);
    $sliceFrom = static::sliceFrom($string, $slice);

    return array($sliceTo, $sliceFrom);
  }

  /**
   * Slice a string from a certain point
   */
  public static function sliceFrom($string, $slice)
  {
    $slice = strpos($string, $slice);

    return substr($string, $slice);
  }

  /**
   * Slice a string up to a certain point
   */
  public static function sliceTo($string, $slice)
  {
    $slice = strpos($string, $slice);

    return substr($string, 0, $slice);
  }

  /**
   * Slice off the end of a string.
   */
  public static function sliceOffEnd($string, $slice)
  {
    if ($slice > 0) {
      $slice *= -1;
    }

    return substr($string, 0, $slice);
  }

  /**
   * Slice off the end of a string.
   */
  public static function sliceOffFront($string, $slice)
  {
    if ($slice < 0) {
      $slice *= -1;
    }

    return substr($string, $slice);
  }

  ////////////////////////////////////////////////////////////////////
  /////////////////////////////// ALTER //////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Remove part of a string
   */
  public static function remove($string, $remove)
  {
    // If we only have one string to remove
    if(!is_array($remove)) $string = str_replace($remove, null, $string);

    // Else, use Regex
    else $string =  preg_replace('#(' .implode('|', $remove). ')#', null, $string);

    // Trim and return
    return trim($string);
  }

  /**
   * Correct arguments order for str_replace
   */
  public static function replace($string, $replace, $with)
  {
    return str_replace($replace, $with, $string);
  }

  /**
   * Toggles a string between two states
   *
   * @param  string  $string The string to toggle
   * @param  string  $first  First value
   * @param  string  $second Second value
   * @param  boolean $loose  Whether a string neither matching 1 or 2 should be changed
   * @return string          The toggled string
   */
  public static function toggle($string, $first, $second, $loose = false)
  {
    // If the string given match none of the other two, and we're in strict mode, return it
    if (!$loose and !in_array($string, array($first, $second))) {
      return $string;
    }

    return $string == $first ? $second : $first;
  }

  /**
   * Slugifies a string
   */
  public static function slugify($string, $separator = '-')
  {
    $string = preg_replace('/[_]/', ' ', $string);

    return static::slug($string, $separator);
  }

  /**
   * Explode a string into an array
   */
  public static function explode($string, $with, $limit = null)
  {
    if (!$limit) return explode($with, $string);

    return explode($with, $string, $limit);
  }

  /**
   * Lowercase a string
   *
   * @param string $string
   *
   * @return string
   */
  public static function lower($string)
  {
    return mb_strtolower($string);
  }

  /**
   * Lowercase a string
   *
   * @param string $string
   *
   * @return string
   */
  public static function upper($string)
  {
    return mb_strtoupper($string);
  }

  /**
   * Convert a string to title case
   *
   * @param string $string
   *
   * @return string
   */
  public static function title($string)
  {
    return mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
  }

  ////////////////////////////////////////////////////////////////////
  /////////////////////////// CASE SWITCHERS /////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Convert a string to PascalCase
   *
   * @param string  $string
   *
   * @return string
   */
  public static function toPascalCase($string)
  {
    return static::studly($string);
  }

  /**
   * Convert a string to snake_case
   *
   * @param string  $string
   *
   * @return string
   */
  public static function toSnakeCase($string)
  {
    return preg_replace_callback('/([A-Z])/', function($match) {
      return '_'.strtolower($match[1]);
    }, $string);
  }

  /**
   * Convert a string to camelCase
   *
   * @param string  $string
   *
   * @return string
   */
  public static function toCamelCase($string)
  {
    return static::camel($string);
  }


    /**
     * Transliterate a UTF-8 value to ASCII.
     *
     * @param  string  $value
     * @return string
     */
    public static function ascii($value)
    {
        return \Patchwork\Utf8::toAscii($value);
    }

    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    public static function camel($value)
    {
        return lcfirst(static::studly($value));
    }

    /**
     * Determine if a given string contains a given sub-string.
     *
     * @param  string        $haystack
     * @param  string|array  $needle
     * @return bool
     */
    public static function contains($haystack, $needle)
    {
        foreach ((array) $needle as $n)
        {
            if (strpos($haystack, $n) !== false) return true;
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given needle.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        return $needle == substr($haystack, strlen($haystack) - strlen($needle));
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param  string  $value
     * @param  string  $cap
     * @return string
     */
    public static function finish($value, $cap)
    {
        return rtrim($value, $cap).$cap;
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string  $pattern
     * @param  string  $value
     * @return bool
     */
    public static function is($pattern, $value)
    {
        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        if ($pattern !== '/')
        {
            $pattern = str_replace('*', '(.*)', $pattern).'\z';
        }
        else
        {
            $pattern = '/$';
        }

        return (bool) preg_match('#^'.$pattern.'#', $value);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int     $limit
     * @param  string  $end
     * @return string
     */
    public static function limit($value, $limit = 100, $end = '...')
    {
        if (mb_strlen($value) <= $limit) return $value;

        return mb_substr($value, 0, $limit, 'UTF-8').$end;
    }

    /**
     * Limit the number of words in a string.
     *
     * @param  string  $value
     * @param  int     $words
     * @param  string  $end
     * @return string
     */
    public static function words($value, $words = 100, $end = '...')
    {
        if (trim($value) == '') return '';

        preg_match('/^\s*+(?:\S++\s*+){1,'.$words.'}/u', $value, $matches);

        if (strlen($value) == strlen($matches[0]))
        {
            $end = '';
        }

        return rtrim($matches[0]).$end;
    }

    /**
     * Get the plural form of an English word.
     *
     * @param  string  $value
     * @param  int  $count
     * @return string
     */
    public static function plural($value, $count = 2)
    {
        return Pluralizer::plural($value, $count);
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int     $length
     * @return string
     */
    public static function random($length = 16)
    {
        if (function_exists('openssl_random_pseudo_bytes'))
        {
            $bytes = openssl_random_pseudo_bytes($length * 2);

            if ($bytes === false)
            {
                throw new \RuntimeException('Unable to generate random string.');
            }

            return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        }

        return static::quickRandom($length);
    }

    /**
     * Generate a "random" alpha-numeric string.
     *
     * Should not be considered sufficient for cryptography, etc.
     *
     * @param  int     $length
     * @return string
     */
    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    /**
     * Get the singular form of an English word.
     *
     * @param  string  $value
     * @return string
     */
    public static function singular($value)
    {
        return Pluralizer::singular($value);
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @return string
     */
    public static function slug($title, $separator = '-')
    {
        $title = static::ascii($title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

        // Convert all dashes/undescores into separator
        $flip = $separator == '-' ? '_' : '-';

        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        $replace = '$1'.$delimiter.'$2';

        return ctype_lower($value) ? $value : strtolower(preg_replace('/(.)([A-Z])/', $replace, $value));
    }

    /**
     * Determine if a string starts with a given needle.
     *
     * @param  string  $haystack
     * @param  string|array  $needle
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle)
        {
            if (strpos($haystack, $needle) === 0) return true;
        }

        return false;
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    public static function studly($value)
    {
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));

        return str_replace(' ', '', $value);
    }
}
