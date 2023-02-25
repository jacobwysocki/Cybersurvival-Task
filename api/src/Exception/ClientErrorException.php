<?php
/**
* Custom Exception php
*
 * This should be thrown if there is erroneous input
* from the client (e.g. invalid method, auth errors)
 *
 * @author Olayinka Hassan
 */
class ClientErrorException extends Exception
{
    public function __construct($message, $code = 400, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}