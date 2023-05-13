<?php

require 'config/constants.php';
require 'helpers/functions.php';
require_once 'controllers/usersController.php';



// GET http://localhost/book/ ---> afficher la page d'accueil --> liste des étudiants

// GET | POST  http://localhost:/?page=create-student ---> afficher la page formaulaire création étudiant

// GET | POST  http://localhost:/?page=edit-student&id=1 ---> afficher la page d'accueil --> formulaire avec data | modification

// GET  http://localhost:/?page=details-student&id=1 ---> afficher la page de détails

// GET | POST  http://localhost:/?page=delete-student&id=1 ---> confirmation supp / supp

$page = !empty($_GET['page']) ? filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "/";

switch ($page) {
    case '/':
        users_create_action();
        break;
    case 'connexion-action':
        users_connexion_action();
        break;
    case 'home':
        get_users_dashboard_action();
        break;
    case 'edit-user':
        edit_profil_action();
        break;
    case 'destroy':
        destroy_action();
        break;
    default:
        # 404
        break;
}

