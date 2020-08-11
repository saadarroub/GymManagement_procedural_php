<?php 
require_once('../../private/initialize.php');

//can't register user if already has one
//can add other user from users page
$utilisateur_set = find_all_utilisateur();
$utilisateur_count = mysqli_num_rows($utilisateur_set);
mysqli_free_result($utilisateur_set);
if($utilisateur_count > 0){
  redirect_to(url_for('/staff/login.php'));
}

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
    redirect_to(url_for('/staff/login.php'));
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

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/header.php') ?>
<section class="section_register">
  <div class="global-container">
    <div class="card login-form">
      <div class="card-body">
        <h3 class="card-title text-center">Register to SM-Program</h3>
        <div class="card-text">
          <?php echo display_errors($errors); ?>
          <form action="<?php echo url_for('/staff/register.php'); ?>" method="post">
            <div class="form-group">
              <label>Nom</label>
              <input type="text" name="nom" value="<?= h($utilisateur['nom']) ?>" class="form-control form-control-sm"
                placeholder="Tapez votre Nom">
            </div>
            <div class="form-group">
              <label>Prenom</label>
              <input type="text" name="prenom" value="<?php echo h($utilisateur['prenom']) ?>"
                class="form-control form-control-sm" placeholder="Tapez votre Prenom">
            </div>
            <div class="form-group">
              <label>Nom de Utilisateur</label>
              <input type="text" name="username" value="<?php echo h($utilisateur['username']) ?>"
                class="form-control form-control-sm" placeholder="Tapez votre Nom de Utilisateur">
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                placeholder="Password">
            </div>
            <div class="form-group">
              <label>Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control" id="exampleInputPassword1"
                placeholder="Confirm Password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <div class="sign-up">
              You have an account? <a href="<?= url_for('/staff/login.php') ?>">Sign in</a>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</section>


</body>

</html>