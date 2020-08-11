<?php 
require_once('../../../../private/initialize.php');
require_login();

if(!isset($_GET['IdClient'])){
    redirect_to(url_for('staff/client/index.php'));
}
$IdClient = $_GET['IdClient'];
if(!isset($_GET['IdPayment'])){
    redirect_to(url_for('/staff/client/payments/index.php?IdClient=' . h(u($IdClient))));
}
$IdPayment = $_GET['IdPayment'];

//get client
//get last_pay for this client
$client = find_client_by_id($IdClient);
$get_last_pay = $client['last_pay'];

if(is_post_request()){
    $payments = [];
    $payments['IdPayment'] = $IdPayment;
    $payments['date_Payment'] = $_POST['date_Payment'];
    $payments['prix'] = $_POST['prix'];
    $result = update_payment($payments);
    if($result === true){
        //change last_pay after updating (set new date; if this date > last_pay)
        if($payments['date_Payment'] > $get_last_pay){
            update_last_pay($IdClient, $payments['date_Payment']);
        }
        $_SESSION['message'] = "payment modifier avec succès.";
        redirect_to(url_for('/staff/client/payments/index.php?IdClient=' . h(u($IdClient))));
      }else{
        $errors = $result;
      }   
   
}  else{
    //show form with this payment
    $payments = find_payment_by_id($IdPayment);
}
    
    
?>


<?php $page_title = 'Modifier paiement'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
        <?php include(SHARED_PATH . '/card_info_client.php'); ?>
            <div class="col-md-8">
            <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('/staff/client/payments/index.php?IdClient='. h(u($IdClient))); ?>">Information du client</a> <i class="fa fa-angle-double-right"></i> Modifier paiement</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Modifier paiement :</h3>
                        <div class="card-text">
                            <?= display_session_message() ?>
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('/staff/client/payments/edit.php?IdPayment='. h(u($IdPayment)) .'&IdClient='. h(u($IdClient))); ?>""
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