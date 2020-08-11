<?php 
require_once('../../private/initialize.php');

if(isset($_SESSION['user_id'])){
  redirect_to(url_for('staff/index.php'));
}

if(is_post_request()) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  //checking errors
  if(is_blank($username)) {
    $errors[] = "Le nom d'utilisateur ne peut pas être vide.";
  }
  if(is_blank($password)) {
    $errors[] = "Le mot de passe ne peut pas être vide.";
  }

  if(empty($errors)) {
    // Using one variable ensures that msg is the same
    $login_failure_msg = "La connexion a échoué.";
    $utilisateur = find_utilisateur_by_username($username);
    if($utilisateur) {
      if(password_verify($password, $utilisateur['Password_User'])) {
        // password matches
        log_in_admin($utilisateur);
        redirect_to(url_for('/staff/index.php'));
      } else {
        $errors[] = $login_failure_msg;
      }
    } else {
      $errors[] = $login_failure_msg;
    }
  }
}else{
$errors = [];
$username = '';
$password = '';
}

//can go to register if no user inserted
//can create just one user in register page
$utilisateur_set = find_all_utilisateur();
$utilisateur_count = mysqli_num_rows($utilisateur_set);
mysqli_free_result($utilisateur_set);

?>



<?php $page_title = 'Login'; ?>
<?php include(SHARED_PATH . '/header.php') ?>
<section class="section_login">
  <div class="global-container">
    <div class="card login-form">
      <div class="card-body">
        <h3 class="card-title text-center">Log in to SM-Program</h3>
        <div class="card-text">
          <?= display_session_message() ?>
          <?= display_errors($errors); ?>
          <form action="<?php echo url_for('/staff/login.php'); ?>" method="post">
            <div class="form-group">
              <label for="exampleInputEmail1">Nom d'utilisateur</label>
              <input type="text" name="username" value="<?php echo h($username); ?>"
                class="form-control form-control-sm" placeholder="Tapez votre Nom d'utilisateur">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Le mot de passe</label>
              <input type="password" name="password" class="form-control form-control-sm" id="exampleInputPassword1"
                placeholder="Le mot de passe">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
            <!-- check if user is not registred -->
            <?php if($utilisateur_count == 0) { ?>
            <div class="sign-up">
              Don't have an account? <a href="<?= url_for('/staff/register.php') ?>">Create One</a>
            </div>
            <?php } ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</body>

</html>