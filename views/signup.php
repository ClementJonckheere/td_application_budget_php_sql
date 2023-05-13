<?php
ob_start();
?>
<main>
        <div class="header-container-inscription">
            <div class="container-logo">
                <img src="./assets/bankcount.png" class="picture-logo-signup" alt="Logo Banckcount"/>
            </div>
        </div>
        <div class="container">
                <form action="" method="post">
                  <div class="container-inscription">
                    <div class="formulaire-inscription">
                        <div class="name-field">
                            <input type="text" class="form-control" placeholder="Nom" id="name" name="name">
                        </div>
                        <div class="username-field">
                            <input type="text" class="form-control" placeholder="Prenom" id="username" name="username">    
                        </div>
                        <div class="email-field">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                        </div>
                        <div class="password-field">
                            <input type="text" class="form-control" placeholder="Mot de passe" id="password" name="password">
                        </div>
                        <div class="submit-field">
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </div>
                        <div class="container-connection-inscription">
                            <a class="inscription" href="./?page=connexion-action">Deja inscrit ?</a>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

</main>

<?php
$content = ob_get_clean();