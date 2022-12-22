<?php
  namespace App\Utilities;

use Carbon\Carbon;

  class Utilities 
  {
    
     /**
     * Converts data to Y-m-d format
     * @param  int  $date
     */
     public static function setDate($date)
     {
        return Carbon::createFromFormat('Y-m-d', $date);
     }

     /**
     * Format date using date_format
     * @param  array  $dates
     */
     public static function dateFormat($date)
     {
        return date_format(date_create($date), 'Y-m-d');
     }

     /**
     * Format dates to dd-mm
     * @param  array  $dates
     */
     public static function formatDates(array $dates)
     {
        $formattedDates = [];
        for ($s=0; $s <count($dates) ; $s++) 
        { 
            array_push($formattedDates, date_format(date_create($dates[$s]), 'd-m'));
        }
        return $formattedDates;
     }

  }