<?php 
require_once('../../private/initialize.php');
require_login();
//number of users
$utilisateur_set = find_all_utilisateur();
$utilisateur_count = mysqli_num_rows($utilisateur_set);

//number of clients active
$client_set = find_active_client();
$client_count = mysqli_num_rows($client_set);

//number of sports
$sport_set = find_all_sport();
$sport_count = mysqli_num_rows($sport_set);

//get notification number
//set data in tow arrays for easy  access
// * nots 1 : for sub ended soon (2 day before)
// * nots 2 : for sub ended 
// * nots 3 : for sub ended for long time (more than 10 days)

// ** declarations for counting nots
$count_nots = 0;
// ** declaration for nots array
$nots_array = [];
// ** declaration for raisons of nots
$_raisons = [];


while($client_result = mysqli_fetch_assoc($client_set)){
   if($client_result['last_pay'] !== NULL){
      $date_now = new DateTime('now');
      $client_pay_date = new DateTime($client_result['last_pay']);
      $interval = date_diff($date_now, $client_pay_date);
      $count_days = $interval->format('%a');
      if ($count_days >= 28 && $count_days <= 30) { 
         $count_nots++; 
         $nots_array[] = $client_result['IdClient'];
         $_raisons[$client_result['IdClient']] = "L'abonnement de dette personne se termine très bientôt";
      }
      elseif ($count_days > 30 && $count_days <= 40) { 
         $count_nots++; 
         $nots_array[] = $client_result['IdClient'];
         $_raisons[$client_result['IdClient']] = "L'abonnement de cette personne est terminé";
      }
      elseif ($count_days > 40) { 
         $count_nots++;
         $nots_array[] = $client_result['IdClient'];
         $_raisons[$client_result['IdClient']] = "Vous pouvez désactiver cette personne, car son abonnement est terminé il y a longtemps";
      }
   }
}
//output of count nots
$nots_result = $count_nots;
?>



<?php include(SHARED_PATH . '/header.php'); ?>
<section class="section_home">
   <div class="container">
      <div class="row mb-3 table-title">
         <div class="col-lg-3 col-md-6 col-sm-6 col-xm-12 spacing-card">
            <div class="card card-inverse card-success">
               <div class="card-block bg-success">
                  <div class="rotate">
                     <i class="fa fa-user fa-5x"></i>
                  </div>
                  <h6 class="text-uppercase">Clients</h6>
                  <h1 class="display-1"><?php echo h($client_count); ?></h1>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-6 col-sm-6 col-xm-12 spacing-card">
            <div class="card card-inverse card-danger">
               <div class="card-block bg-danger">
                  <div class="rotate">
                     <i class="fa fa-list fa-4x"></i>
                  </div>
                  <h6 class="text-uppercase">Notifications</h6>
                  <h1 class="display-1"><?php echo h($nots_result); ?></h1>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-6 col-sm-6 col-xm-12 spacing-card">
            <div class="card card-inverse card-info">
               <div class="card-block bg-info">
                  <div class="rotate">
                     <i class="fa fa-twitter fa-5x"></i>
                  </div>
                  <h6 class="text-uppercase">Utilisateurs</h6>
                  <h1 class="display-1"><?php echo h($utilisateur_count); ?></h1>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-6 col-sm-6 col-xm-12 spacing-card">
            <div class="card card-inverse card-warning">
               <div class="card-block bg-warning">
                  <div class="rotate">
                     <i class="fa fa-share fa-5x"></i>
                  </div>
                  <h6 class="text-uppercase">Sports</h6>
                  <h1 class="display-1"><?php echo h($sport_count); ?></h1>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="wrapper-fostrap">
   <div class="container-fostrap">
      <div class="content">
         <div class="container">
         <h1 class="table-title text-left">Notifications :</h1>
            <div class="row">
               <!-- loop for get id_client -->
               <?php foreach($nots_array as $not) { ?>
               <!-- loop for searching (reason of not) -->
               <?php foreach($_raisons as $key => $_raison) { ?>
               <!-- get reason of this client -->
               <?php if($not == $key) { ?>
               <!-- find client -->
               <?php $client_has_not = find_client_by_id($not) ?>
               <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                  <div class="card">
                     <div class="img-card">
                        <img src="<?php echo url_for('images/' . $client_has_not['img']); ?>" />
                     </div>
                     <div class="card-content">
                        <h4 class="card-title">
                        <a href="<?php echo url_for('/staff/client/payments/index?IdClient=' . h(u($client_has_not['IdClient']))); ?>">
                           <?php echo strtoupper(h($client_has_not['nom']) . ' ' . h($client_has_not['prenom'])); ?>
                        </a>
                        </h4>
                        <p>
                           <?php echo h($_raison); ?>
                        </p>
                     </div>
                  </div>
               </div>
               <?php } ?>
               <?php } ?>
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>