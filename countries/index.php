<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once '../excel-reader-2.21/excel-reader.php';

$DATA_NOT_FOUND = "Not Available";

$country = $_GET["name"];
$popdata = new Spreadsheet_Excel_Reader("../data/world_population_millions.xls");
$co2data = new Spreadsheet_Excel_Reader("../data/co2_emissions_million_metric_tons.xls");
$oildata = new Spreadsheet_Excel_Reader("../data/oil_production_thousands_barrels_daily.xls");
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

$population = getValue($country, $popdata);
$co2emissions = getValue($country, $co2data);
$oilproduction = getValue($country, $oildata);
$electricproduction = getValue($country, $electricproductiondata);
$electricconsumption = getValue($country, $electricconsumptiondata);
$electriccapacity = getValue($country, $electriccapacitydata);

$output = array(
    array("key" => "Population", "value" => $population, "unit" => "million"),
    array("key" => "CO2 Emissions", "value" => $co2emissions, "unit" => "million metric tons"),
    array("key" => "Oil Production", "value" => $oilproduction, "unit" => "thousand barrels per day"),
    array("key" => "Electricity Production", "value" => $electricproduction, "unit" => "billion kilowatt hours"),
    array("key" => "Electricity Consumption", "value" => $electricconsumption, "unit" => "billion kilowatt hours"),
    array("key" => "Installed Electric Capacity", "value" => $electriccapacity, "unit" => "million kilowatts")
);

// Display some images from Panoramio that are tagged under the coutry
// name, but only if population data exists (else do not show the iframe).
$googlemapsHTML = '<iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBbR7cMawadlPMDkcV2p6Dd9M-Pju1sjj0
    &q=' . $country . '" frameborder="0" width="100%" height="500" scrolling="no"
    marginwidth="0" marginheight="0"> </iframe>';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>World Facts - <?php echo $country; ?></title>
        <script type="text/javascript" src="../jquery-2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="../bootstrap-3.3.2/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../bootstrap-3.3.2/bootstrap.min.css">
        <link rel="stylesheet" href="../bootstrap-3.3.2/bootstrap-theme.min.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="page-header">
                        <h1><?php echo $country; ?></h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php echo $googlemapsHTML; ?>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 col-md-offset-3">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2>Facts:</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Value</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($output as $row) {?>
                            <tr>
                                <td><b><?php echo $row["key"]; ?></b></td>
                                <td><?php echo $row["value"]; ?></td>
                                <td><?php echo $row["unit"]; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 col-md-offset-3">
                </div>
            </div>
        </div>
    </body>
</html>
