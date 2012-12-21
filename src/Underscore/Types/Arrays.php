<?php
/**
 * Arrays
 *
 * Helpers and functions for arrays
 */
namespace Underscore\Types;

use \Closure;
use \Underscore\Traits\Collection;

class Arrays extends Collection
{
  /**
   * Create a new Arrays instance
   */
  public static function create()
  {
    return static::from(array());
  }

  ////////////////////////////////////////////////////////////////////
  ///////////////////////////// GENERATE /////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Generate an array from a range
   */
  public static function range($_base, $stop = null, $step = 1)
  {
    // Dynamic arguments
    if (!is_null($stop)) {
      $start = $_base;
    } else {
      $start = 1;
      $stop = $_base;
    }

    return range($start, $stop, $step);
  }

  /**
   * Fill an array with $times times some $data
   */
  public static function repeat($data, $times)
  {
    $times = abs($times);
    if ($times == 0) return array();
    return array_fill(0, $times, $data);
  }

  ////////////////////////////////////////////////////////////////////
  ///////////////////////////// ANALYZE //////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Search for the index of a value in an array
   */
  public static function search($array, $value)
  {
    return array_search($value, $array);
  }

  /**
   * Check if all items in an array match a truth test
   */
  public static function matches($array, Closure $closure)
  {
    // Reduce the array to only booleans
    $array = (array) static::each($array, $closure);

    // Check the results
    if (sizeof($array) === 0) return true;
    $array = array_search(false, $array, false);

    return is_bool($array);
  }

  /**
   * Check if any item in an array matches a truth test
   */
  public static function matchesAny($array, Closure $closure)
  {
    // Reduce the array to only booleans
    $array = (array) static::each($array, $closure);

    // Check the results
    if (sizeof($array) === 0) return true;
    $array = array_search(true, $array, false);

    return is_int($array);
  }

  /**
   * Check if an item is in an array
   */
  public static function contains($array, $value)
  {
    return in_array($value, $array);
  }

   /**
   * Returns the average value of an array
   *
   * @param  array   $array    The source array
   * @param  integer $decimals The number of decimals to return
   * @return integer           The average value
   */
  public static function average($array, $decimals = 0)
  {
    return round((array_sum($array) / sizeof($array)), $decimals);
  }

  /**
   * Get the size of an array
   */
  public static function size($array)
  {
    return sizeof($array);
  }

  /**
   * Get the max value from an array
   */
  public static function max($array, $closure = null)
  {
    // If we have a closure, apply it to the array
    if ($closure) $array = Arrays::each($array, $closure);

    // Sort from max to min
    arsort($array);

    return Arrays::first($array);
  }

  /**
   * Get the min value from an array
   */
  public static function min($array, $closure = null)
  {
    // If we have a closure, apply it to the array
    if ($closure) $array = Arrays::each($array, $closure);

    // Sort from max to min
    asort($array);

    return Arrays::first($array);
  }

  ////////////////////////////////////////////////////////////////////
  //////////////////////////// FETCH FROM ////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Find the first item in an array that passes the truth test
   */
  public static function find($array, Closure $closure)
  {
    foreach ($array as $key => $value) {
      if ($closure($value, $key)) return $value;
    }

    return $array;
  }

  /**
   * Clean all falsy values from an array
   */
  public static function clean($array)
  {
    return Arrays::select($array, function($value) {
      return (bool) $value;
    });
  }

  /**
   * Get a random string from an array
   */
  public static function random($array, $take = null)
  {
    if (!$take) return $array[array_rand($array)];

    return Arrays::from($array)->shuffle()->first($take)->obtain();
  }

  /**
   * Return an array without all instances of certain values
   */
  public static function without()
  {
    $arguments = func_get_args();
    $array = array_shift($arguments);

    return Arrays::filter($array, function($value) use ($arguments) {
      return !in_array($value, $arguments);
    });
  }

  ////////////////////////////////////////////////////////////////////
  ///////////////////////////// SLICERS //////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Get the first value from an array
   */
  public static function first($array, $take = null)
  {
    if (!$take) return array_shift($array);

    return array_splice($array, 0, $take, true);
  }

  /**
   * Get the last value from an array
   */
  public static function last($array, $take = null)
  {
    if (!$take) return array_pop($array);

    return Arrays::rest($array, -$take);
  }

  /**
   * Get everything but the last $to items
   */
  public static function initial($array, $to = 1)
  {
    $slice = sizeof($array) - $to;

    return Arrays::first($array, $slice);
  }

  /**
   * Get the last elements from index $from
   */
  public static function rest($array, $from = 1)
  {
    return array_splice($array, $from);
  }

  ////////////////////////////////////////////////////////////////////
  ///////////////////////////// ACT UPON /////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Iterate over an array and execute a callback for each loop
   */
  public static function at($array, Closure $closure)
  {
    foreach ($array as $key => $value) {
      $closure($value, $key);
    }

    return $array;
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// ALTER ///////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Iterate over an array and modify the array's value
   */
  public static function each($array, Closure $closure)
  {
    foreach ($array as $key => $value) {
      $array[$key] = $closure($value, $key);
    }

    return $array;
  }

  /**
   * Shuffle an array
   */
  public static function shuffle($array)
  {
    shuffle($array);

    return $array;
  }

  /**
   * Sort an array by key
   */
  public static function sortKeys($array, $direction = 'ASC')
  {
    $direction = (strtolower($direction) == 'desc') ? SORT_DESC : SORT_ASC;
    if ($direction == SORT_ASC) ksort($array);
    else krsort($array);

    return $array;
  }

  /**
   * Implodes an array
   *
   * @param array  $array The array
   * @param string $with  What to implode it with
   *
   * @return String
   */
  public static function implode($array, $with)
  {
    return implode($with, $array);
  }

  /**
   * Find all items in an array that pass the truth test
   */
  public static function filter($array, $closure = null)
  {
    if (!$closure) return Arrays::clean($array);

    return array_filter($array, $closure);
  }

  /**
   * Flattens an array to dot notation
   *
   * @param  array  $array  An array
   * @param  string $parent The parent passed to the child (private)
   * @return array          Flattened array to one level
   */
  public static function flatten($array, $parent = null)
  {
    if(!is_array($array)) return $array;

    $_flattened = array();

    // Rewrite keys
    foreach ($array as $key => $value) {
      if($parent) $key = $parent.'.'.$key;
      $_flattened[$key] = static::flatten($value, $key);
    }

    // Flatten
    $flattened = array();
    foreach ($_flattened as $key => $value) {
      if(is_array($value)) $flattened = array_merge($flattened, $value);
      else $flattened[$key] = $value;
    }

    return $flattened;
  }

  /**
   * Invoke a function on all of an array's values
   */
  public static function invoke($array, $callable, $arguments = array())
  {
    // If the callable has arguments, pass them
    if ($arguments) return array_map($callable, $array, $callable);

    return array_map($callable, $array);
  }

  /**
   * Return all items that fail the truth test
   */
  public static function reject($array, Closure $closure)
  {
    foreach ($array as $key => $value) {
      if (!$closure($value, $key)) $filtered[$key] = $value;
    }

    return $filtered;
  }

  /**
   * Remove the first value from an array
   */
  public static function removeFirst($array)
  {
    array_shift($array);

    return $array;
  }

  /**
   * Remove the last value from an array
   */
  public static function removeLast($array)
  {
    array_pop($array);

    return $array;
  }

  /**
   * Prepend a value to an array
   */
  public static function prepend($array, $value)
  {
    array_unshift($array, $value);

    return $array;
  }

  /**
   * Append a value to an array
   */
  public static function append($array, $value)
  {
    array_push($array, $value);

    return $array;
  }
}