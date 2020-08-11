<?php 
require_once('../../../private/initialize.php');

require_login();

if(is_post_request()){
    $sport = [];
    $sport['nom_Type'] = $_POST['nom_Type'];
    $sport['IdSalle'] = $_POST['IdSalle'];
    $sport['prix'] = $_POST['prix'];
    $result = insert_sport($sport);
    if($result === true){
        $sport['IdType'] = mysqli_insert_id($db);
        $result2 = insert_SportSalle($sport);
        if($result2 === true){
             $_SESSION['message'] = "sport créé avec succès.";
        redirect_to(url_for('/staff/sport/index.php'));
        }
       
      }else{
        $errors = $result;
      }
}else{
    $sport = [];
    $sport['nom_Type'] = '';
    $sport['IdSalle'] = '';
    $sport['prix'] = '';
}

?>


<?php $page_title = 'Sport'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card form_global">
                    <div class="card-body">
                        <h3 class="card-title">Ajouter Sport :</h3>
                        <div class="card-text">
                            <?= display_session_message() ?>
                            <?= display_errors($errors); ?>
                            <form action="<?php echo url_for('/staff/sport/index.php'); ?>" method="post">
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
                                        <!-- end show -->
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
                                <button type="submit" class="btn btn-success btn-block">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <?= display_session_message_two() ?>
                <h1 class="table-title">La liste des sports :</h1>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nom de la Salle</th>
                            <th scope="col">Nom du sport</th>
                            <th scope="col">prix</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <!-- show all sport -->
                        <?php $sport_set = find_all_sport(); ?>
                        <?php while($sport = mysqli_fetch_assoc($sport_set)) { ?>
                        <?php $salle = find_salle_by_id($sport['IdSalle']); ?>
                        <tr>
                            <td><?php echo h($salle['nom_Salle']); ?></td>
                            <td><?php echo h($sport['nom_Type']); ?></td>
                            <td><?php echo h($sport['prix']); ?></td>
                            <td><a class="edit"
                                    href="<?php echo url_for('/staff/sport/edit.php?IdType='. h(u($sport['IdType']))); ?>"><i
                                        class="fa fa-pencil-square-o"></i></a>
                                <a class="delete"
                                    href="<?php echo url_for('/staff/sport/delete.php?IdType='. h(u($sport['IdType']))); ?>"><i
                                        class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                        <!-- end show -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>