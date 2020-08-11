<?php 
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['IdType'])){
    redirect_to(url_for('staff/sport/index.php'));
}
$IdType = $_GET['IdType'];


if(is_post_request()){
    $sport = [];
    $sport['nom_Type'] = $_POST['nom_Type'];
    $sport['IdSalle'] = $_POST['IdSalle'];
    $sport['prix'] = $_POST['prix'];
    $sport['IdType'] = $IdType;
    
    $result = update_sport($sport);
    $result2 = update_SportSalle($sport);
    if($result === true && $result2 === true){
        $_SESSION['message_two'] = "sport modifier avec succès.";
        redirect_to(url_for('/staff/sport/index.php'));
      }else{
        $errors = $result;
      }
}else{
    $sport = find_sport_by_id($IdType);
}

?>


<?php $page_title = 'Modifier sport'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('staff/sport/index.php'); ?>">Sport</a> <i class="fa fa-angle-double-right"></i> modifier</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Modifier Sport :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('/staff/sport/edit.php?IdType=' . h(u($sport['IdType']))); ?>"
                                method="post">
                                <div class="form-group">
                                    <label>Choisir la Salle <span class="required_inp">*</span></label>
                                    <select class="form-control form-control-sm" name="IdSalle">
                                        <!-- show all sport and salle -->
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
                                    <label>Nom du sport <span class="required_inp">*</span></label>
                                    <input type="text" name="nom_Type" value="<?php echo h($sport['nom_Type']); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Nom du Sport">
                                </div>
                                <div class="form-group">
                                    <label>Prix <span class="required_inp">*</span></label>
                                    <input type="text" name="prix" value="<?php echo h($sport['prix']); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez le prix">
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