<?php
ob_start();
?>
  <div class="container">
    <h1 class="dashboard_title text-center pt-5">Profil de <?= $_SESSION['user_name'] ?></h1>
    <div class="text text-center pt-2">
        <p>Modifer votre <strong>Email</strong> ou votre <strong>Mot de passe</strong></p>
    </div>
        <div class="row">
            <div class="col-md-12 justify-content-center">
                <div class="nav p-3 justify-content-center">
                    <a class="btn btn-danger me-3 text-light" href="./?page=home">Retour accueil</a>
                </div>
                <form action="" method="post" class="">
                <div class="row g-3 justify-content-center">
                    <div class="col-md-6 pt-5 pb-2">
                        <h2 class="">Modifier votre Profil</h2>
                    </div>
                </div>
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email"  name="email" value="<?= $_SESSION["email"]?>">
                        <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION["user_id"]?>">
                        <div class="col-12 mb-3">
                        <button type="submit" name="envoyer_email" class="btn btn-primary mt-2">Modifier</button>
                        </div>
                    </div>
                </form>
                <form action="" method="post">
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="text" class="form-control" id="password"  name="password" placeholder="*****">
                        <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION["user_id"]?>">
                        <div class="col-12 mb-3">
                            <button type="submit" name="envoyer_password" class="btn btn-primary mt-2">Modifier</button>
                        </div>
                        </div>
                    </div>
                    </div>
                </form>
                <?php
                if (!empty($erreur)) {
                ?> 
                  <div id="erreur"><?php echo $erreur ?></div>
                <?php
                }
                ?>
            </div>
        </div>
  </div>
<?php
$content = ob_get_clean();