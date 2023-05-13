<?php

function createUsers($data) {
    $db = db_connect();
    $sql = <<<EOD
        INSERT INTO `users`
            (
                `name`, 
                `surname`, 
                `email`, 
                `password`
            ) 
        VALUES 
            (
                :name, 
                :surname, 
                :email, 
                :password
            );
     EOD;


     $stmt = $db->prepare($sql);
     return $stmt->execute($data);

}

function getUserById($data)
{
    $db = db_connect();
    $sql = <<<EOD
     SELECT  
        `title`, 
        `date`, 
        `amount`,
        `description`,
        `cat_id`,
        `type_id`,
        `user_id`
     FROM transactions
     WHERE `user_id` = :user_id;
     EOD;
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function connectUser($email) {
    $db = db_connect();
    $sql = "SELECT user_id, name, surname, email, password AS password_hash 
    FROM `users` 
    WHERE `email` = ? ";
     $stmt = $db->prepare($sql);
     $stmt->execute(array($email));
     $result = $stmt->fetchAll();
     return $result;

}

function get_current_transactions($user_id) {
    $db = db_connect();
    $sql = 'SELECT t.*, c.cat_name, ty.type_name
            FROM transactions t 
            LEFT JOIN categories c ON t.cat_id = c.cat_id 
            LEFT JOIN transaction_types ty ON t.type_id = ty.type_id 
            WHERE t.user_id = ? AND MONTH(t.date) = MONTH(CURRENT_DATE)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($user_id));
    $result = $stmt->fetchAll();
    return $result;
}


function get_all_categories(){
    $db = db_connect();
    $sql = <<<EOD
    SELECT * FROM `categories`
    EOD;
    $postsStmt = $db->query($sql);
    $posts = $postsStmt->fetchAll();
    return $posts;
}

function get_all_transaction_types(){
    $db = db_connect();
    $sql = <<<EOD
    SELECT * FROM `transaction_types`
    EOD;
    $postsStmt = $db->query($sql);
    $posts = $postsStmt->fetchAll();
    return $posts;
}

function createTransaction($data){
    $db = db_connect();
    $sql = <<<EOD
        INSERT INTO `transactions`
            (
                `title`, `date`, `amount`,`description`,`cat_id`,`type_id`,`user_id`
            ) 
        VALUES 
            (
                :title, :date, :amount, :description, :cat_id, :type_id, :user_id
            );
     EOD;
     $stmt = $db->prepare($sql);
     return $stmt->execute($data);    
}

function getCategoriesByTransactionType($data)
{
    $db = db_connect();
    $sql = "SELECT `cat_id`, `cat_name`, `cat_color` FROM `categories` WHERE `transac_type_id` = :transac_type_id";    
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getAllUsersByCategories($user_id)
{
    $db = db_connect();
    $sql = "SELECT 
        `cat_name`,
        `cat_color`,
        `title`,
        `date`, 
        SUM(`amount`) AS amount, 
        `categories`.`cat_id`, 
        `user_id`
    FROM `transactions`
    INNER JOIN `categories` ON `categories`.`cat_id`= `transactions`.`cat_id`
    WHERE `user_id` = ? 
    GROUP BY `categories`.`cat_id`, `title`, `date`";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($user_id));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllDepenseAmountByDays($user_id)
{
    $db = db_connect();
    $sql = "SELECT
        `date`,
        CASE (DATE_FORMAT(date, '%w'))
        WHEN 0 THEN 6
        WHEN 1 THEN 0
        WHEN 2 THEN 1
        WHEN 3 THEN 2
        WHEN 4 THEN 3
        WHEN 5 THEN 4
        WHEN 6 THEN 5
        END AS jour,
        SUM(`amount`) AS totalPerDay,
        `user_id`
    FROM
        `transactions` AS TR
    INNER JOIN `transaction_types` AS TT
    ON
        TT.`type_id` = TR.`type_id`
    WHERE
        `user_id` = ? AND DATE > DATE_SUB(NOW(), INTERVAL 7 DAY) AND TR.`type_id` = 1
    GROUP BY `date`
    ORDER BY `jour`";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($user_id));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function parseAllDepenseAmountByDays($user_id){
    $dataDepenses = [];
    $depenses = getAllDepenseAmountByDays($user_id);
    for ($i=0; $i < 7; $i++) { 
        $dataDepense = 0;
        foreach ($depenses as $depense) {
            if($i == $depense["jour"]){
                $dataDepense = $depense["totalPerDay"];
            }
        }
        $dataDepenses[] = $dataDepense;
    }
    return $dataDepenses;
}


function updateEmail($new_email,$user_id){
    $db = db_connect();
    $sql ="UPDATE
        `users`
    SET
        `email` = ?
    WHERE
        `user_id` = ?";
    $stmt = $db->prepare($sql);
    $status = $stmt->execute(array($new_email,$user_id));

    if($status){
        $_SESSION["email"] = $new_email;
    }
}

function updatePassword($user_id, $password){
    $db = db_connect();
    $sql ="UPDATE
        `users`
    SET
        `password` = ? 
    WHERE
        `user_id` = ?";
    $new_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare($sql);
    $status = $stmt->execute(array($new_password, $user_id));
    $stmt->debugDumpParams();
    if($status){
        $_SESSION["password"] = $new_password;
    }
    return $status;
}
// SELECT 
//         `type_name`,
//         SUM(`amount`),
//         `user_id`
//     FROM `transactions`
//     INNER JOIN `transaction_types` ON `transaction_types`.`type_id`= `transactions`.`type_id`
//     WHERE `user_id` = 1 AND date > DATE_SUB(NOW(), INTERVAL 7 DAY)
//     GROUP BY `type_name` WITH ROLLUP

