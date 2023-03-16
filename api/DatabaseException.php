<?php
class databaseException extends Exception {
    public function errorMessage($error) {
        $errorMsg = 'Error on line ' .$this->getLine().' in '.$this->getFile().': <b>'.$error.'</b>';
        return $errorMsg;
    }
}
?>