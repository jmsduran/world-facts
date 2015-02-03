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

require_once "controller.php";
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
                                <th>Year Measured</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($output as $row) {?>
                            <tr>
                                <td><b><?php echo $row["key"]; ?></b></td>
                                <td><?php echo $row["value"]; ?></td>
                                <td><?php echo $row["unit"]; ?></td>
                                <td><?php echo $row["date"]; ?></td>
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
