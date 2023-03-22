<?php

/**
 * Endpoint - A parent class for most of the other endpoints
 *
 * Sets the data to be an array with three elements.
 * If the there is no data it will display a message about successful connection but no data available. That way it is still able to display the 200 code.
 * Checks the parameters used in request against an array of
 * valid parameters for the endpoint.
 * Checks the methods used in request.
 *
 * @param array $params An array of valid param names (e.g. ['id'])
 * @author Jakub Wysocki
 */
abstract class EndpointTwo
{
    private $data;
    private $sql;
    private $sqlParams;

    public function __construct() {

        $db = new DatabaseTwo('../db/db.sqlite');
        $this->initialiseSQL();
        $data = $db->executeSQL($this->sql, $this->sqlParams);
        $this->validateParams($this->endpointParams());
        $this->validateRequestMethod($this->endpointMethod());

        $this->setData( array(
            "length" => count($data),
            "message" => "Success",
            "data" => $data
        ));

        if(count($data)===0){
            $this->setData( array(
                "length" => count($data),
                "message" => "Successful connection, no results found.",
                "data" => null
            ));
        }
    }

    public function POST(){}

    protected function setSQL($sql) {
        $this->sql = $sql;
    }

    protected function getSQL() {
        return $this->sql;
    }

    protected function setSQLParams($params) {
        $this->sqlParams = $params;
    }

    protected function getSQLParams() {
        return $this->sqlParams;
    }

    protected function initialiseSQL() {
        $sql = "";
        $this->setSQL($sql);
        $this->setSQLParams([]);
    }

    protected function setData($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    protected function endpointParams() {
        return [];
    }

    protected function endpointMethod() {
        return [];
    }


    protected function validateParams($params) {
        foreach ($_GET as $key => $value) {
            if (!in_array($key, $params)) {
                throw new ClientErrorException("Invalid Parameter: " . $key, 400);
            }
        }
    }

}