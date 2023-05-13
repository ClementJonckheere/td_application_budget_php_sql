<?php
    session_start();
// Action controleur permettant de créer un utilisateur
function users_create_action(){
    // Appel du /modèle M
    require_once __DIR__ . './../models/models_budget.php';

    if (!empty($_POST)) {
        $options = [
            'name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'surname' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_VALIDATE_EMAIL,
            'password' => 
            [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => [
                    'regexp' =>'/^(?=.[a-z])(?=.[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/'
            ]
        ],
    ];
    
    $processedData = filter_input_array(INPUT_POST, $options);  
        
    extract($processedData);
    $password = $_POST['password'];

    if ($email && $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); 
        $processedData['password'] = $hashed_password;
        createUsers($processedData);
        echo 'ok';
    }
      

    }
    $title = 'Inscription';
    render('signup.php', compact('title'));

}

function users_connexion_action(){
    require_once WEBROOT . './models/models_budget.php';

    if (isset($_POST['email']) && isset($_POST['password'])) {

        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        $user = connectUser($email);
        $password_verify = password_verify($password, $user[0]["password_hash"]);
        
        if ($password_verify) { //utilisateur est connecté
            $_SESSION['user_id'] = $user[0]['user_id'];
            $_SESSION['user_name'] = $user[0]['name']; 
            $_SESSION['user_surname'] = $user[0]['surname'];
            $_SESSION['email'] = $user[0]['email'];
            $_SESSION['password'] = $user[0]['password_hash'];
            header('Location:./?page=home');
            // echo 'OK';

        }else{
            echo 'Email ou mot de passe incorrect';
        }
    }

    $title = 'Connexion';
    render('signin.php', compact('title'));

}





function get_users_dashboard_action(){
    require_once WEBROOT . './models/models_budget.php';
    // $transactions = get_current_transactions($_SESSION[`user_id`]);
    // Partie logique applicative ==> contrôleur

    if(isset($_POST['ajax'])) {
        $transac_type_id = filter_input(INPUT_POST, 'type_id', FILTER_SANITIZE_NUMBER_INT);
        $categories = getCategoriesByTransactionType(compact('transac_type_id'));
        echo json_encode($categories);
        header("HTTP/1.1 302 Found");
        header("Location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }

    //formulaire 
    $resultat = "";
    if (!empty($_POST)) {
        $options = [
            'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'date' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'amount' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'description' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'cat_id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'type_id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'user_id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ];
    
        $processedData = filter_input_array(INPUT_POST, $options);
    
        if($processedData["title"] && $processedData["date"] && $processedData["amount"]) {
            createTransaction($processedData);
            echo $resultat;
        }
    };
    $userexpencesByCategorie = getAllUsersByCategories($_SESSION['user_id']);
    $depenseAmountByDays = parseAllDepenseAmountByDays($_SESSION['user_id']);
    $title = 'Home';

    render('Home.php', compact('title', 'userexpencesByCategorie','depenseAmountByDays'));
}




function edit_profil_action(){
    require_once WEBROOT . './models/models_budget.php';    
    $title = 'Edit';

    if(isset($_POST["email"]) && isset($_POST["user_id"])) {
        $email = htmlspecialchars($_POST['email']);
        $user_id = htmlspecialchars($_POST['user_id']);
        if ($email && $user_id){
           $updateEmail = updateEmail($email,$user_id);
           var_dump($updateEmail);
        }
    }

    if(isset($_POST["password"]) && isset($_POST["user_id"])) {
        $password = htmlspecialchars($_POST['password']);
        $user_id = htmlspecialchars($_POST['user_id']);
        var_dump($user_id);
        if ($password && $user_id){
            $updatePassword = updatePassword($user_id, $password);
            var_dump($updatePassword);
        }
    }



    render('edit-user.php', compact('title'));
}


function destroy_action(){
    require_once WEBROOT . './models/models_budget.php';    

    session_start();
    session_destroy();
    header('location: ./?page=connexion-action');
    exit;

    render('destroy.php', compact('title'));
}