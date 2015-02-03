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
    $x = null;
    $r = $d->rowcount(0);

    for ($i = 5; $i <= $r; $i++) {
        $a = strtolower($d->val($i, 'A'));
        $b = strtolower($c);

        if ($a == $b) {
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

    if ($i != null) {
        $g = $d->val($i, 'G');
    }

    return $g;
}

$population = getValue($country, $popdata);
$co2emissions = getValue($country, $co2data);
$oilproduction = getValue($country, $oildata);
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
                    <p>Population: <?php echo $population; ?></p>
                    <p>CO2 Emissions: <?php echo $co2emissions; ?></p>
                    <p>Oil Production: <?php echo $oilproduction; ?></p>
                </div>
            </div>
        </div>
    </body>
</html>
