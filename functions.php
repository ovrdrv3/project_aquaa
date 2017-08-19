<?php
// return fontawesome icon
function i($code){
    $icon = '<i class="fa fa-'.$code.'"></i>';
    return $icon;
}

$format = 'Y-m-d';
// For readout
$nice_format = 'M j';

function nice_date($date) {
    $newresult = \DateTime::createFromFormat($GLOBALS['format'], $date);
    return date_format($newresult, $GLOBALS['nice_format']);
}

function nice_days($number_of_days){

    $result = "";

    if ($number_of_days <= 5){
        for($i = 0; $i < $number_of_days; $i++){
            // This is the font awesome icon for water droplet.
            $result .= '<i class="fa fa-tint" aria-hidden="true"></i>';
        }
    } else {
        $result = $number_of_days . " days";
    }

    return $result;

}

// echo nice_date("2017-06-20");

function get_next_date($last_watered_YMD, $water_frequency){
    $today = new DateTime("now");
    $selector = '';
    // DateTime Object
    $optimal_water_date = \DateTime::createFromFormat($GLOBALS['format'], $last_watered_YMD);
    // add $water_frequency to $optimal_water_date
    $optimal_water_date->modify('+' . $water_frequency .' day');
    return date_format($optimal_water_date, 'Y-m-d');
}

// date function that is a switch and returns a tag with a class
// based on how many days have passed relative to the needs of the plant
function get_days_left($last_watered_YMD, $water_frequency, $tag = null) {

    $today = new DateTime("now");
    $selector = '';
    // DateTime Object
    $optimal_water_date = \DateTime::createFromFormat($GLOBALS['format'], $last_watered_YMD);
    // add $water_frequency to $optimal_water_date
    $optimal_water_date->modify('+' . $water_frequency .' day');

    // subtract the amount of days passed since watering
    // example: $water_frequency = 7 days, $days_until_water = 8 days,
    $days_until_water = $today->diff($optimal_water_date);

    // echo "This is optimal_water_date: " . $optimal_water_date->format('Y-m-d') . "</br>";
    // echo "This is days_until_water: " . $days_until_water->days . "</br>";
    // If tag param is present, return a tag with css values
    if($tag) {
        // if it is not time to water the plants yet
        if ($days_until_water->invert == 0) {
            // if ($days_until_water->days <= 2) {
            //     $selector = 'water-almost-ready';
            // } else {
                $selector = 'water-not-ready';
            // }

        } else {
            if ($days_until_water->days > 1) {
                $selector = 'water-past-date';
            } else {
                $selector = 'water-ready';
            }
        }
        return $tag . '="' . $selector . '"';
    } else {
        if ($days_until_water->invert == 0) {
            return $days_until_water->days;
        } else {
            return "0";
        }
    }
}


 ?>
