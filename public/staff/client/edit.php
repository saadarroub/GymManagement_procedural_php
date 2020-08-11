<?php 
//TO DO : remove image from images when i update 
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['IdClient'])){
    redirect_to(url_for('staff/client/index.php'));
}
$IdClient = $_GET['IdClient'];


if(is_post_request()){
   //infos files
   $files_infos = [];
   $files_infos['img_name'] = $_FILES['img']['name'] ?? '';
   $files_infos['img_temp'] = $_FILES['img']['tmp_name'] ?? '';
   $files_infos['size'] = $_FILES['img']['size'] ?? '';
   $files_infos['error'] = $_FILES['img']['error'] ?? '';

   //get random name for file
   $img_random = rand(0, 200000);
   $img_extension = get_extenssion($files_infos['img_name']);
   $files_infos['new_img_name'] = $img_random . '.' . $img_extension;

   //values for insert client
   $client = [];
   $client['nom'] = $_POST['nom'] ?? '';
   $client['prenom'] = $_POST['prenom'] ?? '';
   $client['Tel'] = $_POST['Tel'] ?? '';
   $client['active'] = $_POST['active'] ?? '';
   $client['img'] = $files_infos['img_temp'] ?? '';

   //values for insert sport client
   $client['IdType'] = $_POST['IdType'] ?? '';
   $salle_row = find_salle_by_sport($client['IdType']);
   $client['IdSalle'] = $salle_row['IdSalle'];
   $client['IdClient'] = $IdClient;

   $result = update_client($client, $files_infos);
   if($result === true){
       $result2 = update_sport_client($client);
       if($result2 === true){
       //set the destination : when i store my images
       $destination = $_SERVER['DOCUMENT_ROOT'] . url_for('images/' . $files_infos['new_img_name']);
       //move images to my_destination
       move_uploaded_file($files_infos['img_temp'], $destination);
       $_SESSION['message'] = "client modifier avec succès.";
       redirect_to(url_for('/staff/client/payments/index?IdClient=' . h(u($client['IdClient']))));
       }
       } 
   else{
   $errors = $result;
   }
}else{
    //show from with this client
    $client = find_client_by_id($IdClient);
}

?>


<?php $page_title = 'Modifier client'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
        <?php include(SHARED_PATH . '/card_info_client.php'); ?>
            <div class="col-md-8">
            <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('/staff/client/payments/index.php?IdClient='. h(u($IdClient))); ?>">Information du client</a> <i class="fa fa-angle-double-right"></i> Modifier client</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Modifier client :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('/staff/client/edit.php?IdClient=' . h(u($client['IdClient']))) ; ?>"
                                method="post" runat="server" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nom du Client <span class="required_inp">*</span></label>
                                    <input type="text" name="nom" value="<?= h($client['nom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez le Nom">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prenom du client <span class="required_inp">*</span></label>
                                    <input type="text" name="prenom" value="<?= h($client['prenom']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez le Prenom">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tel <span class="required_inp">*</span></label>
                                    <input type="text" name="Tel" value="<?= h($client['Tel']) ?>"
                                        class="form-control form-control-sm" placeholder="Tapez Tel">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">choisir une nouvel image</label>
                                    <input type='file' name='img' id="img_inp" />
                                </div>
                                <div class="form-group">
                                   <img id="img_preview" src="#"/>
                                </div>
                                <div class="form-group">
                                    <label>Choisir la Salle et le Sport<span class="required_inp">*</span></label>
                                    <!-- show all salle sport of this client -->
                                    <select class="form-control form-control-sm" name="IdType">
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
                                </div>
                                <div class="form-group">
                                <label>Changer le statue</label>
                                    <div class="custom-control custom-switch">
                                    <input type="hidden" name="active" value="0" />
                                    <input type="checkbox" name="active" value="1" <?php if($client['active'] == "1") { echo " checked"; } ?> class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"><?php echo ($client['active'] == "1") ? 'Desactiver' : 'Activer'; ?></label>
                                    </div>
                                </div>
                                <!-- end show -->
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