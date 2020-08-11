<?php 
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['IdUser'])){
    redirect_to(url_for('staff/utilisateur/index.php'));
}
$IdUser = $_GET['IdUser'];

//check : can't delete the current user from this page
$utilisateur = find_utilisateur_by_id($IdUser);
$id_user_from_db = $utilisateur['IdUser'];
$current_id_user = $_SESSION['user_id'];
if($id_user_from_db == $current_id_user){
    $_SESSION['message_info'] = "vous ne pouvez pas supprimer l'utilisateur actuel, utilisez la page de profil.";
    redirect_to(url_for('staff/utilisateur/index.php'));
}


if(is_post_request()){
    $utilisateur = [];
    //data for deleting : entre pass of user is required
    $utilisateur['password'] = $_POST['password'];
    $utilisateur['user_id'] = $_SESSION['user_id'];
    $utilisateur['IdUser'] = $IdUser;
    $result = delete_utilisateur($utilisateur);
    if($result === true){
        $_SESSION['message_two'] = "utilisateur supprimer avec succès.";
        redirect_to(url_for('/staff/utilisateur/index.php'));
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
            <div class="col-md-12">
            <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('staff/utilisateur/index.php'); ?>">Utilisateur</a> <i class="fa fa-angle-double-right"></i> supprimer</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Supprimer Utilisateur :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('/staff/utilisateur/delete.php?IdUser=' . h(u($utilisateur['IdUser']))); ?>"
                                method="post">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" disabled name="nom" value="<?= h($utilisateur['nom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Nom">
                                </div>
                                <div class="form-group">
                                    <label>Prenom</label>
                                    <input type="text" disabled name="prenom" value="<?php echo h($utilisateur['prenom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Prenom">
                                </div>
                                <div class="form-group">
                                    <label>Nom de Utilisateur</label>
                                    <input type="text" disabled name="username" value="<?php echo h($utilisateur['username']) ?>"
                                        class="form-control form-control-sm"
                                        placeholder="Tapez votre Nom de Utilisateur">
                                </div>
                                <div class="form-group">
                                    <label>Taper votre mot de passe <span class="required_inp">*</span></label>
                                    <p><i class="fa fa-exclamation-circle"></i> pour des raisons de sécurité, votre mot
                                        de passe est requis pour supprimer cet enregistrement.</p>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                                <button type="submit" class="btn btn-danger btn-block">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>