<?php
require_once("Config.php");
require_once("../models/Model.php");
require_once("../models/ChartModel.php");
use \cs174\hw4\configs\Config as Config;
use \cs174\hw4\models\ChartModel as ChartModel;

/**
 * PHP script which utilizes the Config class
 * in order to connect to a DBMS and create
 * a new database which will be used to store
 * all information for the PasteChart website
 */

// first attempt to establish a connection to the database
$mysqli = new mysqli(Config::DB_HOST, Config::DB_USERNAME, Config::DB_PASSWORD, "", Config::DB_PORT);
if($mysqli->connect_errno) {
    echo("Error connecting to \"" . Config::DB_HOST . "\" with username \"" . Config::DB_USERNAME
        . "\", password \"" . Config::DB_PASSWORD . "\", and port \"" . Config::DB_PORT . "\".");
    return;
}

// if connection worked, create and initialize the database
echo("Connection success, now setting up database " . Config::DB_DATABASE . " (any existing database with " .
    "this name will be deleted completely, and some initial sample data will be loaded into the database)\n");

// create the database and use it
$mysqli->query("DROP DATABASE IF EXISTS " . Config::DB_DATABASE);
$mysqli->query("CREATE DATABASE " . Config::DB_DATABASE);
$mysqli->query("USE " . Config::DB_DATABASE);

// create the Chart relation, which has information for each user-created chart (hash of data, title of chart, and data)
//   [multiply max line length by max lines to get how long data string can be]
$mysqli->query("DROP TABLE IF EXISTS Chart");
$mysqli->query("CREATE TABLE Chart(md5 CHAR(32), " .
    "title VARCHAR(". Config::MAX_TITLE_LENGTH ."), " .
    "data VARCHAR(". (Config::MAX_DATA_LINES * Config::MAX_DATA_LINE_LENGTH) . "), " .
    "PRIMARY KEY (md5))");

// add sample data to the database
//   note that there are some lacking entries in the $data; this is to test that we can leave slots empty
$title = "Rabbit and Wolf Population Over Time Chart";
$data = "Jan,600,5.4\nFeb,450,5.0\nMar,400,4.8\nApr,380,4.5\nMay,450,4.0\nJun,500,3.8\nJul,400,4.6\nAug,50,10.11";
$md5 = hash("md5", $data);
$cm = new ChartModel();
$result = $cm->insertChartEntry($md5, $title, $data);
if(!$result) {
    echo("Failed to add sample tuple data to database!\n");
}
else {
    echo("Successfully added sample tuple data to database!\n");
}

$mysqli->close();

// let user know process has finished successfully
echo("Done! The database should be ready for use now\n");

?>