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

if(is_post_request()){
    $payments = [];
    $payments['IdPayment'] = $IdPayment;
    //data for deleting : entre pass of user is required
    $payments['user_id'] = $_SESSION['user_id'];
    $payments['password'] = $_POST['password'];
    $result = delete_payment($payments);
    if($result === true){
        //get max date payment for this client
        $max_date_payment = find_max_date_payment_by_id($IdClient);
        //change last_pay after deleting (set max date or null if no more dates registred)
        update_last_pay($IdClient, $max_date_payment);
        $_SESSION['message'] = "payment supprimer avec succès.";
        redirect_to(url_for('/staff/client/payments/index.php?IdClient=' . h(u($IdClient))));
      }else{
        $errors = $result;
      }   
   
} 
//show this payment
$payments = find_payment_by_id($IdPayment);   
    
?>


<?php $page_title = 'Supprimer paiement'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<section class="section_global">
    <div class="container">
        <div class="row">
        <?php include(SHARED_PATH . '/card_info_client.php'); ?>
            <div class="col-md-8">
            <div class="text-right table-title">
                     <p class="for-back"><a class="text-right" href="<?php echo url_for('/staff/client/payments/index.php?IdClient='. h(u($IdClient))); ?>">Information du client</a> <i class="fa fa-angle-double-right"></i> Supprimer paiement</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Supprimer paiement :</h3>
                        <div class="card-text">
                            <?= display_errors($errors); ?>
                            <form
                                action="<?php echo url_for('/staff/client/payments/delete.php?IdPayment='. h(u($IdPayment)) .'&IdClient='. h(u($IdClient))); ?>""
                                method="post">
                                <div class="form-group">
                                    <label>date de paiement</label>
                                    <input disabled type="date" name="date_Payment" value="<?= h($payments['date_Payment']); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez la date">
                                </div>
                                <div class="form-group">
                                    <label>Prix</label>
                                    <input disabled type="text" name="prix" value="<?= h($payments['prix']); ?>"
                                        class="form-control form-control-sm" placeholder="Tapez le prix">
                                </div>
                                <div class="form-group">
                                    <label>Taper votre mot de passe <span class="required_inp">*</span></label>
                                    <p><i class="fa fa-exclamation-circle"></i> pour des raisons de sécurité, votre mot
                                        de passe est requis pour supprimer cet enregistrement.</p>
                                    <input type="password" name="password" class="form-control"
                                        id="exampleInputPassword1" placeholder="Password">
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