<?php 
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['IdUser'])){
    redirect_to(url_for('staff/utilisateur/index.php'));
}
$IdUser = $_GET['IdUser'];

if(is_post_request()){
    $utilisateur = [];
    $utilisateur['IdUser'] = $IdUser;
    $utilisateur['nom'] = $_POST['nom'];
    $utilisateur['prenom'] = $_POST['prenom'];
    $utilisateur['username'] = $_POST['username'];
    $utilisateur['password'] = '';

    $result = update_utilisateur($utilisateur);
    if($result === true){
        $_SESSION['message_two'] = "utilisateur modifier avec succès.";
        redirect_to(url_for('/staff/utilisateur/index.php'));
      }else{
        $errors = $result;
      }

}else{
    $utilisateur = find_utilisateur_by_id($IdUser);
}

?>


<?php $page_title = 'Modifier utilisateur'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('staff/utilisateur/index.php'); ?>">Utilisateur</a> <i class="fa fa-angle-double-right"></i> modifier</p>
                </div>
            <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Modifier Utilisateur :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form action="<?php echo url_for('/staff/utilisateur/edit.php?IdUser=' . h(u($utilisateur['IdUser']))); ?>" method="post">
                                <div class="form-group">
                                    <label>Nom <span class="required_inp">*</span></label>
                                    <input type="text" name="nom" value="<?= h($utilisateur['nom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Nom">
                                </div>
                                <div class="form-group">
                                    <label>Prenom <span class="required_inp">*</span></label>
                                    <input type="text" name="prenom" value="<?php echo h($utilisateur['prenom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Prenom">
                                </div>
                                <div class="form-group">
                                    <label>Nom de Utilisateur <span class="required_inp">*</span></label>
                                    <input type="text" name="username" value="<?php echo h($utilisateur['username']) ?>"
                                        class="form-control form-control-sm"
                                        placeholder="Tapez votre Nom de Utilisateur">
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Modifier</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>