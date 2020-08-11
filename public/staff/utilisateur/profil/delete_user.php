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
        $utilisateur['IdUser'] = $IdUser;
        $utilisateur['user_id'] = $IdUser;
        $result = delete_utilisateur($utilisateur);
        if($result === true){
            $_SESSION['message'] = "utilisateur supprimer avec succès.";
            redirect_to(url_for('/staff/logout.php'));
        }else{
            $errors = $result;
        }
    
}
?>

<?php $page_title = 'Supprimer Utilisateur'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <h1 class="table-title text-left">Supprimer l'utilisateur :</h1>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('staff/utilisateur/profil/delete_user.php?IdUser=' . h(u($IdUser))); ?>"
                                method="post">
                                <div class="form-group">
                                    <label>Taper votre mot de passe <span class="required_inp">*</span></label>
                                    <p><i class="fa fa-exclamation-circle"></i> pour des raisons de sécurité, votre mot
                                        de passe est requis pour supprimer cet enregistrement.</p>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                                <input class="btn btn-danger btn-block" type="submit" value="Supprimer l'utilisateur"
                                    name="supprimer_user">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>