<?php
namespace cs174\hw4\models;
use cs174\hw4\configs\Config as Config;

/**
 * Class Model
 * @package cs174\hw4\models
 *
 * Superclass for any class used
 * as the Model for the PasteChart
 * website
 */
class Model {

    /**
     * Creates and returns a database connection for the PasteChart website
     * @return \mysqli the database connection (based on settings in Config.php)
     */
    protected function getDatabaseConnection() {
        return new \mysqli(Config::DB_HOST, Config::DB_USERNAME, Config::DB_PASSWORD, Config::DB_DATABASE, Config::DB_PORT);
    }
}
?>