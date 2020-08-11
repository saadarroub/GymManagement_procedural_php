<?php 
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['IdType'])){
    redirect_to(url_for('staff/sport/index.php'));
}
$IdType = $_GET['IdType'];


if(is_post_request()){
    $sport = [];
    //data for deleting : entre pass of user is required
    $sport['password'] = $_POST['password'];
    $sport['user_id'] = $_SESSION['user_id'];
    $sport['IdType'] = $IdType;
    $result = delete_sport($sport);
    if($result === true){
        $_SESSION['message_two'] = "sport supprimer avec succès.";
        redirect_to(url_for('/staff/sport/index.php'));
      }else{
        $errors = $result;
      }
}
$sport = find_sport_by_id($IdType);
?>


<?php $page_title = 'Supprimer sport'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('staff/sport/index.php'); ?>">Sport</a> <i class="fa fa-angle-double-right"></i> supprimer</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Supprimer Sport :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('/staff/sport/delete.php?IdType=' . h(u($sport['IdType']))); ?>"
                                method="post">
                                <div class="form-group">
                                    <label>Choisir la Salle</label>
                                    <select class="form-control form-control-sm" disabled name="IdSalle">
                                        <!-- show this sport and salle -->
                                        <?php 
                                        $salle_set = find_all_salle();
                                        while($salle = mysqli_fetch_assoc($salle_set)) {
                                          echo "<option value=\"" . h($salle['IdSalle']) . "\"";
                                          if($sport["IdSalle"] == $salle['IdSalle']) {
                                            echo " selected";
                                          }
                                          echo ">" . h($salle['nom_Salle']) . "</option>";
                                        }
                                        mysqli_free_result($salle_set);
                                        ?>
                                        <!-- end show  -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nom du sport</label>
                                    <input type="text" disabled name="nom_Type"
                                        value="<?php echo h($sport['nom_Type']); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Nom du Sport">
                                </div>
                                <div class="form-group">
                                    <label>Prix</label>
                                    <input type="text" disabled name="prix" value="<?php echo h($sport['prix']); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez le prix">
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