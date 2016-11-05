<?php
namespace cs174\hw4\models;

/**
 * Class ChartModel
 * @package cs174\hw4\models
 *
 * Model for storing and retrieving
 * chart entries in the database
 */
class ChartModel extends Model {

    /**
     * Inserts given parameters into the Chart schema in the PasteChart database
     * @param $md5 String md5 hash of the chart data
     * @param $title String title of the chart
     * @param $data String chart data
     * @return bool true if the data entry was successful, and false otherwise
     */
    public function insertChartEntry($md5, $title, $data) {
        // reject if any of the parameters are null or empty strings
        if(is_null($md5) || is_null($title) || is_null($data)
        || strcmp($md5, '') === 0 || strcmp($title, '') === 0 || strcmp($data, '') === 0) {
            return false;
        }
        $mysqli = parent::getDatabaseConnection();
        $statement = $mysqli->stmt_init();
        $statement->prepare("INSERT INTO Chart VALUES(?, ?, ?)");
        $statement->bind_param("sss", $md5, $title, $data);
        $statement->execute();
        $statement->get_result();
        if($statement->affected_rows === 0) { // check if any rows were affected to determine if insert worked or not
            $statement->close();
            $mysqli->close();
            return false;
        }
        $statement->close();
        $mysqli->close();
        return true;
    }

    /**
     * Retrieves data from database based on md5 parameter in the PasteChart database
     * @param $md5 String md5 hash of the chart data to retrieve from the database
     * @return bool|\mysqli_result false if the query failed (most likely an incorrect md5 given), or else
     * the query result which will contain the tuple in Chart that has the matching md5 value
     */
    public function getChartEntry($md5) {
        // reject if $md5 is null or empty string
        if(is_null($md5) || strcmp($md5, '') === 0) {
            return false;
        }
        $mysqli = parent::getDatabaseConnection();
        $statement = $mysqli->stmt_init();
        $statement->prepare("SELECT * FROM Chart WHERE md5 = ?");
        $statement->bind_param("s", $md5);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        $mysqli->close();
        return $result;
    }
}
?>