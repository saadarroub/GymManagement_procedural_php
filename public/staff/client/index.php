<?php 
require_once('../../../private/initialize.php');
require_login();


if(is_post_request()){
    $client = [];
    $client['IdType'] = $_POST['IdType'];
    $client['nom'] = $_POST['nom'];
    $client['active'] = $_POST['active'];
    $client_filter = filter_client($client);
}else{
    $client['IdType'] = '';
    $client['nom'] = '';
    $client_filter = find_all_client(); 
}
 
?>

<?php $page_title = 'Client'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>
<!-- include search div -->
<?php include(SHARED_PATH . '/search.php'); ?>
<section class="wrapper-fostrap">
    <div class="container-fostrap">
        <div class="content">
            <div class="container">
            <div class="text-right">
                    <p class="for-back"><a class="text-right"
                            href="<?php echo url_for('staff/client/new.php');?>">Ajouter
                            client</a></p>
                </div>
                <?= display_session_message() ?>
                <div class="row"> 
                    <!-- show all clients -->
                    <?php while($client_result = mysqli_fetch_assoc($client_filter)) { ?>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card <?php echo ($client_result['active'] == "1") ? 'active-client' : 'inactive-client'; ?>">
                            <a class="img-card"
                                href="<?php echo url_for('/staff/client/payments/index?IdClient=' . h(u($client_result['IdClient']))); ?>">
                                <img src="<?php echo url_for('images/' . $client_result['img']); ?>" />
                            </a>
                            <div class="card-content">
                                <h4 class="card-title">
                                    <a
                                        href="<?php echo url_for('/staff/client/payments/index?IdClient=' . h(u($client_result['IdClient']))); ?>">
                                        <?php echo strtoupper(h($client_result['nom']) . ' ' . h($client_result['prenom'])); ?>
                                    </a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- and show -->
                </div>
            </div>
        </div>
    </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>