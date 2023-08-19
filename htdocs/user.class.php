<?php
/**
 * User Class
 * This class is for database related (connect, insert, update, and delete) operations
 * @auther Praveen M S
 * @date 2021-09-18
 */

class user
{
    private $dbHost     = DB_HOST;
    private $dbUsername = DB_USERNAME;
    private $dbPassword = DB_PASSWORD;
    private $dbName     = DB_NAME;
    private $userTbl    = 'users';

    public function __construct()
    {
        if(!isset($this->db)) {
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error) {
                die("Failed to connect with MySQL: " . $conn->connect_error);
            } else {
                $this->db = $conn;
            }
        }
    }

    public function CheckUser($data = array())
    {
        if (!empty($data)) {
            // Check whether user data already exists in database
            $prevQuery = "SELECT * FROM ".$this->userTbl." WHERE oauth_provider = '".$data['oauth_provider']."' AND oauth_uid = '".$data['oauth_uid']."'";
            $prevResult = $this->db->query($prevQuery);

            // Add modified time to the data array
            $data['modified'] = date("Y-m-d H:i:s");
            if ($prevResult->num_rows > 0) {
                $colvalset = '';
                $i = 0;
                foreach($data as $key=>$val) {
                    $comma = ($i < count($data) - 1) ? ', ' : '';
                    $colvalset .= $key."='".$this->db->real_escape_string($val)."'".$comma;
                    $i++;
                }
                $query = "UPDATE ".$this->userTbl." SET ".$colvalset." WHERE oauth_provider = '".$data['oauth_provider']."' AND oauth_uid = '".$data['oauth_uid']."'";
                $update = $this->db->query($query);
            } else {
                // Add created time to the data array
                if (!array_key_exists("created", $data)) {
                    $data['created'] = date("Y-m-d H:i:s");
                }

                $columns = $values = '';
                $i = 0;
                foreach($data as $key=>$val) {
                    $comma = ($i < count($data) - 1) ? ', ' : '';
                    $columns .= $key.$comma;
                    $values .= "'".$this->db->real_escape_string($val)."'".$comma;
                    $i++;
                }

                $query = "INSERT INTO ".$this->userTbl." (".$columns.") VALUES (".$values.")";
                $insert = $this->db->query($query);
            }
            // Get the user data from database
            $result = $this->db->query($prevQuery);
            $userData = $result->fetch_assoc();
        }
        // Return user data
        return !empty($userData) ? $userData : false;
    }
}
