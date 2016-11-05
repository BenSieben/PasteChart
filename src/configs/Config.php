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

    // Database connection constants
    const DB_HOST = "127.0.0.1"; // host for the database
    const DB_USERNAME = "root"; // username for user connecting to database
    const DB_PASSWORD = ""; // password for user connecting to database
    const DB_DATABASE = "PasteChart"; // name of database schema to use for all the website data
    const DB_PORT = "3307"; // port that database is on (note how this is NOT default port 3306!)
}
?>