<?php 
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['IdSalle'])){
    redirect_to(url_for('staff/salle/index.php'));
}
$IdSalle = $_GET['IdSalle'];

if(is_post_request()){
    //data for deleting : entre pass of user is required
    $user_id = $_SESSION['user_id'];
    $password = $_POST['password'];
    $result = delete_salle($IdSalle, $password, $user_id);
    if($result === true){
        $_SESSION['message_two'] = "salle supprimer avec succès.";
        redirect_to(url_for('/staff/salle/index.php'));
      }else{
        $errors = $result;
      }
}
    //show form with this salle
    $salle_set = find_salle_by_id($IdSalle);
    $salle = $salle_set['nom_Salle'];
?>


<?php $page_title = 'Supprimer salle'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-right table-title">
                    <p class="for-back"><a class="text-right" href="<?php echo url_for('staff/salle/index.php'); ?>">Salle</a> <i
                            class="fa fa-angle-double-right"></i> supprimer</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Supprimer la Salle :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form action="<?php echo url_for('/staff/salle/delete.php?IdSalle='. h(u($IdSalle))); ?>"
                                method="post">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nom de la Salle</label>
                                    <input type="text" name="nom_Salle" disabled value="<?php echo h($salle); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Nom la Salle">
                                </div>
                                <div class="form-group">
                                    <label>Taper votre mot de passe <span class="required_inp">*</span></label>
                                    <p><i class="fa fa-exclamation-circle"></i> pour des raisons de sécurité, votre mot
                                        de passe est requis pour supprimer cet enregistrement.</p>
                                    <input type="password" name="password" class="form-control"
                                        id="exampleInputPassword1" placeholder="Password">
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