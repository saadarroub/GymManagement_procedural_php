<?php 
require_once('../../../private/initialize.php');

require_login();

if(is_post_request()){
    $salle = $_POST['nom_Salle'];
    $result = insert_salle($salle);
    if($result === true){
        $_SESSION['message'] = "Salle créé avec succès.";
        redirect_to(url_for('/staff/salle/index.php'));
      }else{
        $errors = $result;
      }

}else{
    $salle ='';
}

?>


<?php $page_title = 'Salle'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card form_global">
                    <div class="card-body">
                        <h3 class="card-title">Ajouter Salle :</h3>
                        <div class="card-text">
                            <?= display_session_message() ?>
                            <?= display_errors($errors); ?>
                            <form action="<?php echo url_for('/staff/salle/index.php'); ?>" method="post">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nom de la Salle <span class="required_inp">*</span></label>
                                    <input type="text" name="nom_Salle" value="<?php echo h($salle); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez votre Nom la Salle">
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <?= display_session_message_two() ?>
                <h1 class="table-title">La liste des salles :</h1>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nom de la Salle</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <!-- show all salles -->
                        <?php $salle_set = find_all_salle(); ?>
                        <?php while($salle = mysqli_fetch_assoc($salle_set)) { ?>
                        <tr>
                            <td><?php echo h($salle['nom_Salle']); ?></td>
                            <td><a class="edit"
                                    href="<?php echo url_for('/staff/salle/edit.php?IdSalle='. h(u($salle['IdSalle']))); ?>"><i
                                        class="fa fa-pencil-square-o"></i></a>
                                <a class="delete"
                                    href="<?php echo url_for('/staff/salle/delete.php?IdSalle='. h(u($salle['IdSalle']))); ?>"><i
                                        class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                        <!-- show all salles -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>