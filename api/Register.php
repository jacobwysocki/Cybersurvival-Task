<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require __DIR__ . '/DatabaseTwo.php';
$db = new DatabaseTwo('../db/db.sqlite');


function msg($success, $status, $message, $extra = [])
{
    return array(
        'success' => $success,
        'status' => $status,
        'message' => $message
    , $extra);
}

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (
    !isset($data->firstName)
    || !isset($data->lastName)
    || !isset($data->email)
    || !isset($data->jobRole)
    || !isset($data->password)
    || !isset($data->rankID)
    || empty(trim($data->firstName))
    || empty(trim($data->lastName))
    || empty(trim($data->email))
    || empty(trim($data->jobRole))
    || empty(trim($data->password))
    || empty(trim($data->rankID))
) :

    $fields = ['fields' => ['firstName', 'lastName', 'email', 'password', 'jobRole', 'rankID']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :

    $firstName = trim($data->firstName);
    $lastName = trim($data->lastName);
    $email = trim($data->email);
    $jobRole = trim($data->jobRole);
    $password = trim($data->password);
    $rankID = trim($data->rankID);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
        $returnData = msg(0, 422, 'Invalid Email Address!');

    elseif (strlen($password) < 8) :
        $returnData = msg(0, 422, 'Your password must be at least 8 characters long!');

    elseif (strlen($firstName) < 1) :
        $returnData = msg(0, 422, 'Your firstName must be at least 1 character long!');
        
    elseif (strlen($lastName) < 1) :
        $returnData = msg(0, 422, 'Your lastName must be at least 1 character long!');

    else :
        try {

            $check_email = "SELECT `email` FROM `users` WHERE `email`=:email";
            $check_email_stmt = $db->dbConnection->prepare($check_email);
            $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $check_email_stmt->execute();

            if ($check_email_stmt->rowCount()) :
                $returnData = msg(0, 422, 'This E-mail already in use!');

            else :
                $insert_query = "INSERT INTO `users`(`firstName`,`lastName`,`email`,`jobRole`,`password`, `rankID`) VALUES(:firstName, :lastName, :email, :jobRole, :password, :rankID)";

                $insert_stmt = $db->dbConnection->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':firstName', htmlspecialchars(strip_tags($firstName)), PDO::PARAM_STR);
                $insert_stmt->bindValue(':lastName', htmlspecialchars(strip_tags($lastName)), PDO::PARAM_STR);
                $insert_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $insert_stmt->bindValue(':jobRole', $jobRole, PDO::PARAM_STR);
                $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
                $insert_stmt->bindValue(':rankID', $rankID, PDO::PARAM_STR);

                $insert_stmt->execute();

                $returnData = msg(1, 201, 'You have successfully registered.');

            endif;
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;
endif;

echo json_encode($returnData);