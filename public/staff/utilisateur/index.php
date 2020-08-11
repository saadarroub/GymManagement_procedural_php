<?php 
require_once('../../../private/initialize.php');
require_login();

if(is_post_request()){
    $utilisateur = [];
    $utilisateur['nom'] = $_POST['nom'];
    $utilisateur['prenom'] = $_POST['prenom'];
    $utilisateur['username'] = $_POST['username'];
    $utilisateur['password'] = $_POST['password'];
    $utilisateur['confirm_password'] = $_POST['confirm_password'];
    $result = insert_utilisateur($utilisateur);
    if($result === true){
      $_SESSION['message'] = "Utilisateur créé avec succès.";
      redirect_to(url_for('/staff/utilisateur/index.php'));
    }else{
      $errors = $result;
    }
  }else{
    $utilisateur = [];
    $utilisateur['nom'] = '';
    $utilisateur['prenom'] = '';
    $utilisateur['username'] = '';  
  }

?>


<?php $page_title = 'Utilisateur'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card form_global">
                    <div class="card-body">
                        <h3 class="card-title">Ajouter Utilisateur :</h3>
                        <div class="card-text">
                            <?= display_session_message() ?>
                            <?= display_errors($errors); ?>
                            <form action="<?php echo url_for('/staff/utilisateur/index.php'); ?>" method="post">
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
                                <div class="form-group">
                                    <label>Password <span class="required_inp">*</span></label>
                                    <input type="password" name="password" class="form-control"
                                        id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password <span class="required_inp">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control"
                                        id="exampleInputPassword1" placeholder="Confirm Password">
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                  <?= display_session_message_two() ?>
                  <?= display_session_message_info() ?>
                  <h1 class="table-title">La liste des Utilisateurs :</h1>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Prenom</th>
                            <th scope="col">Username</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <!-- show all users -->
                        <?php $utilisateur_set = find_all_utilisateur(); ?>
                        <?php while($utilisateur = mysqli_fetch_assoc($utilisateur_set)) { ?>
                        <tr>
                            <td><?php echo h($utilisateur['Nom']); ?></td>
                            <td><?php echo h($utilisateur['Prenom']); ?></td>
                            <td><?php echo h($utilisateur['UserName']); ?></td>
                            <td><a class="edit"
                                    href="<?php echo url_for('/staff/utilisateur/edit.php?IdUser='. h(u($utilisateur['IdUser']))); ?>"><i class="fa fa-pencil-square-o"></i></a>
                                <a class="delete"
                                    href="<?php echo url_for('/staff/utilisateur/delete.php?IdUser='. h(u($utilisateur['IdUser']))); ?>"><i class="fa fa-trash-o"></i></a>
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