<?php 
require_once('../../../../private/initialize.php');
require_login();

if(!isset($_GET['IdUser'])){
    redirect_to(url_for('staff/index.php'));
}
$IdUser = $_GET['IdUser'];


if(is_post_request()){
    $utilisateur = [];
    //data for deleting : entre pass of user is required
    $utilisateur['password'] = $_POST['password'];
    $utilisateur['new_password'] = $_POST['new_password'];
    $utilisateur['confirm_password'] = $_POST['confirm_password'];
    $utilisateur['IdUser'] = $IdUser;
    $result = update_pass_utilisateur($utilisateur);
    if($result === true){
        $_SESSION['message'] = "mot de passe modifier avec succès.";
        redirect_to(url_for('/staff/logout.php'));
      }else{
        $errors = $result;
      }
    
}
$utilisateur = find_utilisateur_by_id($IdUser);
?>


<?php $page_title = 'Supprimer Utilisateur'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <h1 class="table-title text-left">Changer le mot de passe :</h1>
            <div class="col-md-12">
                <div class="text-right table-title">
                    <p class="for-back"><a class="text-right"
                            href="<?php echo url_for('staff/utilisateur/profil/delete_user.php?IdUser=' . h(u($IdUser))); ?>">Supprimer
                            l'utilisateur</a></p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('staff/utilisateur/profil/edit_mdp.php?IdUser=' . h(u($IdUser))); ?>"
                                method="post">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" disabled name="nom" value="<?= h($utilisateur['nom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Nom">
                                </div>
                                <div class="form-group">
                                    <label>Prenom</label>
                                    <input type="text" disabled name="prenom"
                                        value="<?php echo h($utilisateur['prenom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Prenom">
                                </div>
                                <div class="form-group">
                                    <label>Nom de Utilisateur</label>
                                    <input type="text" disabled name="username"
                                        value="<?php echo h($utilisateur['username']) ?>"
                                        class="form-control form-control-sm"
                                        placeholder="Tapez votre Nom de Utilisateur">
                                </div>
                                <div class="form-group">
                                    <label>Taper votre mot de passe <span class="required_inp">*</span></label>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label>Neaveau mot de passe <span class="required_inp">*</span></label>
                                    <input type="password" name="new_password" class="form-control"
                                        placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label>confirm passe <span class="required_inp">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control"
                                        placeholder="Password">
                                </div>
                                <input class="btn btn-success btn-block" type="submit" value="Modifier le mot de passe"
                                    name="modifier_mdp">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>