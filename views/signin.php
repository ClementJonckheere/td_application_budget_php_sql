<?php
ob_start();
?>
<main>
    <div class="header-container-connection">
        <div class="title-welcome">
            <h1>Bienvenue !</h1>
        </div>
        <div class="container-logo">
            <img src="./assets/bankcount.png" class="picture-logo" alt="Logo Banckcount"/>
        </div>
    </div>
    <form action="" method="post">
        <div class="container-connection">
            <div class="formulaire-connection">
                <div class="email-field">
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                </div>
                <div class="password-field">
                    <input type="text" class="form-control" id="password" placeholder="Mot de passe" name="password">
                </div>
                <div class="submit-field">
                        <button type="submit" name="connexion" class="btn btn-primary">Se connecter</button>
                </div>
            </div>
            <div class="container-connection-inscription">
                <p>Pas de compte Bankcount ? </p><a class="btn-inscrire" href="./?page=/"> s'inscrire</a>
            </div>

        </div>
    </div>
    </form>
</main>

<?php
$content = ob_get_clean();