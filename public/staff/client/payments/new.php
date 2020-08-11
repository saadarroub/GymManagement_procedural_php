<?php 
require_once('../../../../private/initialize.php');

require_login();

if(!isset($_GET['IdClient'])){
    redirect_to(url_for('staff/client/index.php'));
}
$IdClient = $_GET['IdClient'];

//get client -> salle -> sport for this client (for inserting payment)
$client = find_client_by_id($IdClient);
$sport = find_sport_by_id($client['IdType']);
$salle = find_salle_by_id($sport['IdSalle']);

//get last_pay for this client
$get_last_pay = $client['last_pay'];

if(is_post_request()){
    $payments = [];
    $payments['IdType'] = $sport['IdType'];
    $payments['IdSalle'] = $salle['IdSalle'];
    $payments['IdClient'] = $IdClient;
    $payments['date_Payment'] = $_POST['date_Payment'];
    $payments['prix'] = $_POST['prix'];
    $result = insert_payment($payments);
    if($result === true){
        //change last_pay after adding (set other date; if this date > last_pay or new date if last_pay = null)
        if($payments['date_Payment'] > $get_last_pay){
            update_last_pay($IdClient, $payments['date_Payment']);
        }
        $_SESSION['message'] = "payment créé avec succès.";
        redirect_to(url_for('/staff/client/payments/index.php?IdClient=' . $client['IdClient']));
      }else{
        $errors = $result;
      }   
   
}  else{
    //show form 
    $payments = [];
    $payments['date_Payment'] = '';
    $payments['prix'] = '';
}
    
    
?>


<?php $page_title = 'Ajouter paiement'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
        <?php include(SHARED_PATH . '/card_info_client.php'); ?>
            <div class="col-md-8">
            <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('/staff/client/payments/index.php?IdClient='. h(u($IdClient))); ?>">Information du client</a> <i class="fa fa-angle-double-right"></i> Ajouter paiement</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Ajouter paiement :</h3>
                        <div class="card-text">
                            <?= display_session_message() ?>
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('/staff/client/payments/new.php?IdClient='. h(u($IdClient))); ?>"
                                method="post">
                                <div class="form-group">
                                    <label>date de paiement <span class="required_inp">*</span></label>
                                    <input type="date" name="date_Payment" value="<?= h($payments['date_Payment']); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez la date">
                                </div>
                                <div class="form-group">
                                    <label>Prix <span class="required_inp">*</span></label>
                                    <input type="text" name="prix" value="<?= h($payments['prix']); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez le prix">
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>