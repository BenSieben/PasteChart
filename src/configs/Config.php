<?php
namespace cs174\hw4\configs;

/**
 * Class Config
 * @package cs174\hw4\configs
 *
 * Contains constants needed
 * for the PasteChart website.
 * Change these as necessary
 * to get the website properly
 * working
 */
class Config {

    //Constant for the full URL to where the index.php site is for PasteChart
    //   (leave out /index.php at end to load files correctly when used)
    const BASE_URL = "http://192.168.2.131/hw4";
    //const BASE_URL = "http://10.250.22.186/hw4";

    // Database connection constants
    const DB_HOST = "127.0.0.1"; // host for the database
    const DB_USERNAME = "root"; // username for user connecting to database
    const DB_PASSWORD = ""; // password for user connecting to database
    const DB_DATABASE = "PasteChart"; // name of database schema to use for all the website data
    const DB_PORT = "3307"; // port that database is on (note how this is NOT default port 3306!)

    // Chart information constants
    const MAX_TITLE_LENGTH = 100; // max number of characters for a chart title
    const MAX_DATA_LINES = 50; // max number of lines (rows) for data
    const MAX_DATA_LINE_LENGTH = 80; // max number of characters per line of data
    const MAX_VALUES_PER_LINE = 5; // max number of values that can be associated with any line of the graph
}
?>