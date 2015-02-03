<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once '../excel-reader-2.21/excel-reader.php';

$DATA_NOT_FOUND = "Not Available";

$country = $_GET["name"];
$popdata = new Spreadsheet_Excel_Reader("../data/world_population_millions.xls");
$co2data = new Spreadsheet_Excel_Reader("../data/co2_emissions_million_metric_tons.xls");
$oildata = new Spreadsheet_Excel_Reader("../data/oil_production_thousands_barrels_daily.xls");

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

// Display some images from Panoramio that are tagged under the coutry
// name, but only if population data exists (else do not show the iframe).
$googlemapsHTML = ($population == $DATA_NOT_FOUND) ? '' : '<iframe
    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBbR7cMawadlPMDkcV2p6Dd9M-Pju1sjj0
    &q=' . $country . '" frameborder="0" width="100%" height="300" scrolling="no"
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
                <div class="col-md-4 col-md-offset-4">
                    <div class="jumbotron">
                        <h1><?php echo $country; ?></h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <?php echo $googlemapsHTML; ?>
                </div>
            </div>
             <div class="row">
                <div class="col-md-4 col-md-offset-4">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Facts:</h3>
                        </div>
                        <div class="panel-body">
                            <p><b>Population:</b> <?php echo $population; ?> million.</p>
                            <p><b>CO2 Emissions:</b> <?php echo $co2emissions; ?> million metric tons.</p>
                            <p><b>Oil Production:</b> <?php echo $oilproduction; ?> thousand barrels per day.</p>
                        </div>
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-md-4 col-md-offset-4">
                </div>
            </div>
        </div>
    </body>
</html>
