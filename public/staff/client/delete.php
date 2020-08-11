<?php 
//TO DO : remove image from images when i delete
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['IdClient'])){
    redirect_to(url_for('staff/client/index.php'));
}
$IdClient = $_GET['IdClient'];

if(is_post_request()){
    $client = [];
    //data for deleting : entre pass of user is required
    $client['password'] = $_POST['password'];
    $client['user_id'] = $_SESSION['user_id'];
    $client['IdClient'] = $IdClient;
    $result = delete_client($client);
    if($result === true){
        $_SESSION['message'] = "client supprimer avec succès.";
        redirect_to(url_for('/staff/client/index.php'));
      }else{
        $errors = $result;
      }
   
}
//show form with this client
$client = find_client_by_id($IdClient);

?>


<?php $page_title = 'Supprimer Client'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
        <?php include(SHARED_PATH . '/card_info_client.php'); ?>
            <div class="col-md-8">
            <div class="text-right table-title">
                    <p class="for-back"><a class="text-right" href="<?php echo url_for('/staff/client/payments/index.php?IdClient='. h(u($IdClient))); ?>">Information du client</a>
                    <i class="fa fa-angle-double-right"></i> Supprimer client</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Supprimer Client :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form action="<?php echo url_for('/staff/client/delete.php?IdClient=' . h(u($client['IdClient']))) ; ?>" method="post" runat="server"
                                enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nom du Client</label>
                                    <input type="text" disabled name="nom" value="<?= h($client['nom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez le Nom">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prenom du client</label>
                                    <input type="text" disabled name="prenom" value="<?= h($client['prenom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez le Prenom">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tel</label>
                                    <input type="text" disabled name="Tel" value="<?= h($client['Tel']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez Tel">
                                </div>
                                <div class="form-group">
                                    <label>Choisir la Salle</label>
                                    <!-- show salle sport of this client -->
                                    <select disabled class="form-control form-control-sm" name="IdType">
                                    <?php 
                                    $sport_set = find_all_sport();
                                    while($sport = mysqli_fetch_assoc($sport_set)){    
                                    $salle = find_salle_by_id($sport['IdSalle']);     
                                    echo "<option value=\"" . h($sport['IdType']) . "\"";
                                    if($client["IdType"] == $sport['IdType']) {
                                    echo " selected";
                                    }
                                    echo ">" . h($salle['nom_Salle']) .' - ' . h($sport['nom_Type']) . "</option>"; 
                                    }
                                    mysqli_free_result($sport_set);
                                    ?>
                                    </select>
                                    <!-- end show -->
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