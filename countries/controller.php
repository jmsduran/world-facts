<?php
/**
 * world-facts
 * A PHP application that displays world census and energy data.
 * Copyright (C) 2015  James Marcos Duran
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

error_reporting(E_ALL ^ E_NOTICE);
require_once '../excel-reader-2.21/excel-reader.php';

$DATA_NOT_FOUND = "Not Available";

$country = $_GET["name"];

/**
 * Data source files.
 */
$popdata = new Spreadsheet_Excel_Reader("../data/world_population_millions.xls");
$co2data = new Spreadsheet_Excel_Reader("../data/co2_emissions_million_metric_tons.xls");
$oilproductiondata = new Spreadsheet_Excel_Reader("../data/oil_production_thousands_barrels_daily.xls");
$oilconsumptiondata = new Spreadsheet_Excel_Reader("../data/oil_consumption_thousands_barrels_daily.xls");
$electricproductiondata = new Spreadsheet_Excel_Reader("../data/electricity_production_billion_kilowatt_hours.xls");
$electricconsumptiondata = new Spreadsheet_Excel_Reader("../data/electricity_consumption_billion_kilowatt_hours.xls");
$electriccapacitydata = new Spreadsheet_Excel_Reader("../data/electricity_capacity_million_kilowatts.xls");

/**
 * Finds the row index of a given country name.
 *
 * $c country name
 * $d XLS data object
 */
function findIndex($c, $d) {
    $x = $DATA_NOT_FOUND;
    $r = $d->rowcount(0);

    for ($i = 5; $i <= $r; $i++) {
        $a = strtolower($d->val($i, 'A'));
        $b = strtolower($c);

        if ($a === $b) {
            $x = $i;
            break;
        }
    }

    return $x;
}

/**
 * Retrieves the XLS column value of a given country name.
 * In this case, for all data sources we retrieve the column 'G',
 * since it refers to the most recent entry within the row.
 *
 * $c country name
 * $d XLS data object
 */
function getValue($c, $d) {
    $i = findIndex($c, $d);
    $g = $DATA_NOT_FOUND;

    if ($i != $DATA_NOT_FOUND) {
        $g = $d->val($i, 'G');
    }

    return $g;
}

/**
 * Retrieves from the data source the year the metric was evaluated.
 *
 * $d XLS data object.
 */
function getDateMeasured($d) {
    $g = $d->val(3, 'G');
    return (!empty($g)) ? $g : $DATA_NOT_FOUND;
}

/**
 * Raw values from the data source files.
 */
$population = getValue($country, $popdata);
$co2emissions = getValue($country, $co2data);
$oilproduction = getValue($country, $oilproductiondata);
$oilconsumption = getValue($country, $oilconsumptiondata);
$electricproduction = getValue($country, $electricproductiondata);
$electricconsumption = getValue($country, $electricconsumptiondata);
$electriccapacity = getValue($country, $electriccapacitydata);

/**
 * Formatted output to be displayed to the user.
 */
$output = array(
    array("key" => "Population", "value" => $population, "unit" => "million", "date" => getDateMeasured($popdata)),
    array("key" => "CO2 Emissions", "value" => $co2emissions, "unit" => "million metric tons", "date" => getDateMeasured($co2data)),
    array("key" => "Oil Production", "value" => $oilproduction, "unit" => "thousand barrels per day", "date" => getDateMeasured($oilproductiondata)),
    array("key" => "Oil Consumption", "value" => $oilconsumption, "unit" => "thousand barrels per day", "date" => getDateMeasured($oilconsumptiondata)),
    array("key" => "Electricity Production", "value" => $electricproduction, "unit" => "billion kilowatt hours", "date" => getDateMeasured($electricproductiondata)),
    array("key" => "Electricity Consumption", "value" => $electricconsumption, "unit" => "billion kilowatt hours", "date" => getDateMeasured($electricconsumptiondata)),
    array("key" => "Installed Electric Capacity", "value" => $electriccapacity, "unit" => "million kilowatts", "date" => getDateMeasured($electriccapacitydata))
);

// Display an embedded Google Maps frame of the country.
$googlemapsHTML = '<iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBbR7cMawadlPMDkcV2p6Dd9M-Pju1sjj0
    &q=' . $country . '" frameborder="0" width="100%" height="500" scrolling="no"
    marginwidth="0" marginheight="0"> </iframe>';
?>
