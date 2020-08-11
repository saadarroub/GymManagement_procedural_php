<?php 
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['IdSalle'])){
    redirect_to(url_for('staff/salle/index.php'));
}
$IdSalle = $_GET['IdSalle'];

if(is_post_request()){
    $salle = $_POST['nom_Salle'];
    $result = update_salle($IdSalle, $salle);
    if($result === true){
        $_SESSION['message_two'] = "salle modifier avec succès.";
        redirect_to(url_for('/staff/salle/index.php'));
      }else{
        $errors = $result;
      }

}else{
    //show form of this salle
    $salle_set = find_salle_by_id($IdSalle);
    $salle = $salle_set['nom_Salle'];
}

?>


<?php $page_title = 'Modifier salle'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('staff/salle/index.php'); ?>">Salle</a> <i class="fa fa-angle-double-right"></i> modifier</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Modifier la Salle :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form action="<?php echo url_for('/staff/salle/edit.php?IdSalle='. h(u($IdSalle))); ?>"
                                method="post">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nom de la Salle <span
                                            class="required_inp">*</span></label>
                                    <input type="text" name="nom_Salle" value="<?php echo h($salle); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Nom la Salle">
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