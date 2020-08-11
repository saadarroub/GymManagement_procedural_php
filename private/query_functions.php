<?php
//user table quries
//find all utilisateur
function find_all_utilisateur() {
  global $db;

  $sql = "SELECT * FROM utilisateur";
  //echo $sql;
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

//find utilisateur by id
function find_utilisateur_by_id($id) {
  global $db;

  $sql = "SELECT IdUser as IdUser, Nom as nom, Prenom as prenom, UserName as username, Password_User as password FROM utilisateur ";
  $sql .= "WHERE IdUser='" . db_escape($db, $id) . "' ";
  // echo $sql;
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $utilisateur = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $utilisateur; // returns an assoc. array
}

//find utilisateur by username
function find_utilisateur_by_username($username){
  global $db;

  $sql = "SELECT * FROM utilisateur ";
  $sql .= "WHERE UserName='" . db_escape($db, $username) . "' ";
  $sql .= "LIMIT 1";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $admin = mysqli_fetch_assoc($result); // find first
  mysqli_free_result($result);
  return $admin; // returns an assoc. array
}

//insert utilisateur
function insert_utilisateur($utilisateur){
    global $db;

    $errors = validate_utilisateur($utilisateur);
    if(!empty($errors)) {
      return $errors;
    }

    $password = password_hash($utilisateur['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO utilisateur (Nom, Prenom, UserName, Password_User) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $utilisateur['nom']) . "',";
    $sql .= "'" . db_escape($db, $utilisateur['prenom']) . "',";
    $sql .= "'" . db_escape($db, $utilisateur['username']) . "',";
    $sql .= "'" . db_escape($db, $password) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
}

//update utilisateur
function update_utilisateur($utilisateur){
  global $db;

  $password_sent = !is_blank($utilisateur['password']);

  $errors = validate_utilisateur($utilisateur, ['password_required' => $password_sent]);
  if(!empty($errors)) {
    return $errors;
  }


  $sql = "UPDATE Utilisateur SET ";
  $sql .= "Nom='" . db_escape($db, $utilisateur['nom']) . "', ";
  $sql .= "Prenom='" . db_escape($db, $utilisateur['prenom']) . "', ";
  if($password_sent){
    $sql .= "Password_User='" . db_escape($db, $utilisateur['password']) . "', ";
  }
  $sql .= "UserName='" . db_escape($db, $utilisateur['username']) . "' ";
  $sql .= "WHERE IdUser='" . db_escape($db, $utilisateur['IdUser']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

function update_pass_utilisateur($utilisateur){
  global $db;

  $errors = validate_pass_user($utilisateur);
  if(!empty($errors)) {
    return $errors;
  }

  $password = password_hash($utilisateur['new_password'], PASSWORD_BCRYPT);

  $sql = "UPDATE Utilisateur SET ";
  $sql .= "Password_User='" . db_escape($db, $password) . "' ";
  $sql .= "WHERE IdUser='" . db_escape($db, $utilisateur['IdUser']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

//validate function for utilisateur
function validate_pass_user($utilisateur){
  $errors = [];

  //password
  $utilisateur_form_db = find_utilisateur_by_id($utilisateur['IdUser']);
  $password_hashed = $utilisateur_form_db['password'];
  if(!password_verify($utilisateur['password'], $password_hashed)){
    $errors[] = "ancien mot de passe incorrect.";
  }else{
    //new password
  if(is_blank($utilisateur['new_password'])) {
    $errors[] = "nouveau mot de passe ne peut pas être vide.";
  } elseif(!has_length($utilisateur['new_password'], ['min' => 8, 'max' => 255])) {
    $errors[] = "nouveau mot de passe doit comprendre entre 8 et 255 caractères.";
  }  elseif(!validate_password($utilisateur['new_password'], $utilisateur['confirm_password'])){
    $errors[] = "vérifiez votre mot de passe.";
  }
  }

  return $errors;
}


//delete utilisateur
function delete_utilisateur($utilisateur){
  global $db;

  $errors = validate_password_for_delete($utilisateur['password'], $utilisateur['user_id']);
  if(!empty($errors)) {
    return $errors;
  }

  $sql = "DELETE FROM Utilisateur ";
  $sql .= "WHERE IdUser='" . db_escape($db, $utilisateur['IdUser']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

//validate function for utilisateur
function validate_utilisateur($utilisateur, $options = []) {
    $errors = [];

    // nom
    if(is_blank($utilisateur['nom'])) {
      $errors[] = "Le nom ne peut pas être vide.";
    } elseif(!has_length($utilisateur['nom'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Nom doit comprendre entre 2 et 255 caractères.";
    }

    // prenom
    if(is_blank($utilisateur['prenom'])) {
      $errors[] = "Le prenom ne peut pas être vide.";
    } elseif(!has_length($utilisateur['prenom'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Prenom doit comprendre entre 2 et 255 caractères.";
    }

    // username
    if(is_blank($utilisateur['username'])) {
      $errors[] = "Le nom d'utilisateur ne peut pas être vide.";
    } elseif(!has_length($utilisateur['username'], ['min' => 5, 'max' => 255])) {
      $errors[] = "username doit comprendre entre 5 et 255 caractères.";
    }
    $current_id = $utilisateur['IdUser'] ?? '0';
    if(!has_unique_username($utilisateur['username'], $current_id)) {
      $errors[] = "le nom d'utilisateur doit être unique.";
    }

    //password
    $password_required = $options['password_required'] ?? true;
    if($password_required){
       if(is_blank($utilisateur['password'])) {
      $errors[] = "Le mot de passe ne peut pas être vide.";
    } elseif(!has_length($utilisateur['password'], ['min' => 8, 'max' => 255])) {
      $errors[] = "password doit comprendre entre 8 et 255 caractères.";
    }elseif(!validate_password($utilisateur['password'], $utilisateur['confirm_password'])){
      $errors[] = "vérifiez votre mot de passe.";
    }
    }
   
    return $errors;
  }


  //salle table quries
  //find all salles
  function find_all_salle(){
    global $db;

    $sql = "SELECT * FROM Salle";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  //find salle by id
  function find_salle_by_id($IdSalle){
    global $db;

    $sql = "SELECT * FROM Salle ";
    $sql .= "WHERE IdSalle='" . db_escape($db, $IdSalle) . "' ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $salle = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $salle; // returns an assoc. array
  }

  //find salle by idType
  function find_salle_by_sport($IdType){
    global $db;

    $sql  = "select * from sportsalle ";
    $sql .= "where IdType='" . $IdType . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $salle = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $salle; // returns an assoc. array
  }

  //insert salle
  function insert_salle($salle){
    global $db;

    $errors = validate_salle($salle);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO Salle (nom_Salle) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $salle) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }


  }

  //update salle
  function update_salle($IdSalle, $salle){
    global $db;

    $errors = validate_salle($salle);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE Salle SET ";
    $sql .= "nom_Salle='" . db_escape($db, $salle) . "' ";
    $sql .= "WHERE IdSalle='" . db_escape($db, $IdSalle) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }
 
  //delete salle 
  function delete_salle($IdSalle, $password, $user_id){
    global $db;

    $errors = validate_password_for_delete($password, $user_id);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "DELETE FROM salle ";
    $sql .= "WHERE IdSalle='" . db_escape($db, $IdSalle) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  //validate function for salle
function validate_salle($salle) {
  $errors = [];

  // nom
  if(is_blank($salle)) {
    $errors[] = "Le nom de la salle ne peut pas être vide.";
  } elseif(!has_length($salle, ['min' => 2, 'max' => 255])) {
    $errors[] = "Nom doit comprendre entre 2 et 255 caractères.";
  }

  return $errors;
}


//sport table quries
//find all sport
function find_all_sport(){
  global $db;

  $sql = "SELECT s.IdSalle,t.nom_Type,s.prix,t.IdType FROM Type_Sport t join SportSalle s on t.IdType = s.IdType ";
  $sql .= "order by s.IdSalle";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

//find sport by id
function find_sport_by_id($IdType){
  global $db;

  $sql = "SELECT s.IdSalle,t.nom_Type,s.prix,t.IdType FROM Type_Sport t join SportSalle s on t.IdType = s.IdType ";
  $sql .= "WHERE t.IdType='" . db_escape($db, $IdType) . "' ";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $sport = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $sport; // returns an assoc. array
}


//insert sport
function insert_sport($sport){
  global $db;

  $errors = validate_sport($sport);
    if(!empty($errors)) {
      return $errors;
    }

  $sql = "INSERT INTO Type_Sport (nom_Type) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $sport['nom_Type']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
}

//insert sport salle
function insert_SportSalle($sport){
  global $db;

  $errors = validate_sport($sport);
  if(!empty($errors)) {
    return $errors;
  }

  $sql = "INSERT INTO SportSalle (IdSalle, IdType, prix) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $sport['IdSalle']) . "', ";
    $sql .= "'" . db_escape($db, $sport['IdType']) . "', ";
    $sql .= "'" . db_escape($db, $sport['prix']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
}

//update sport
function update_sport($sport){
  global $db;

  $errors = validate_sport($sport);
  if(!empty($errors)) {
    return $errors;
  }


  $sql = "UPDATE Type_Sport SET ";
  $sql .= "nom_Type='" . db_escape($db, $sport['nom_Type']) . "' ";
  $sql .= "WHERE IdType='" . db_escape($db, $sport['IdType']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

//update sport salle
function update_SportSalle($sport){
  global $db;

  $errors = validate_sport($sport);
  if(!empty($errors)) {
    return $errors;
  }


  $sql = "UPDATE SportSalle SET ";
  $sql .= "IdSalle='" . db_escape($db, $sport['IdSalle']) . "', ";
  $sql .= "prix='" . db_escape($db, $sport['prix']) . "' ";
  $sql .= "WHERE IdType='" . db_escape($db, $sport['IdType']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

//delete sport 
function delete_sport($sport){
  global $db;

    $errors = validate_password_for_delete($sport['password'], $sport['user_id']);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "DELETE FROM Type_Sport ";
    $sql .= "WHERE IdType='" . db_escape($db, $sport['IdType']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
}

//validate function for sport
function validate_sport($sport) {
  $errors = [];

  // nom_Type
  if(is_blank($sport['nom_Type'])) {
    $errors[] = "Le nom du sport ne peut pas être vide.";
  } elseif(!has_length($sport['nom_Type'], ['min' => 2, 'max' => 255])) {
    $errors[] = "Nom doit comprendre entre 2 et 255 caractères.";
  }

  // prix
  if(is_blank($sport['prix'])) {
    $errors[] = "Le prix ne peut pas être vide.";
  } elseif(!is_numeric($sport['prix'])) {
    $errors[] = "Prix doit être numérique.";
  }

  return $errors;
}

//client table quries
//find all clients
function find_all_client(){
  global $db;

  $sql = "SELECT * FROM clients ";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

//find active clients
function find_active_client(){
  global $db;

  $sql = "SELECT * FROM clients ";
  $sql .= "WHERE active = true";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

//find CLIENT by id
function find_client_by_id($IdClient){
  global $db;

  $sql = "SELECT c.*,s.IdType FROM Clients c JOIN SportClients s ON c.IdClient = s.IdClient ";
  $sql .= "WHERE c.IdClient ='" . db_escape($db, $IdClient) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $sport = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $sport; // returns an assoc. array
}

//filter client 
function filter_client($client){
  global $db;

  $sport_sent = !is_blank($client['IdType']);
  $nom_sent = !is_blank($client['nom']);
  $active_sent = !is_blank($client['active']);


  $sql = "SELECT c.*,s.IdType FROM Clients c JOIN SportClients s ON c.IdClient = s.IdClient ";
  if($nom_sent && $sport_sent && $active_sent){
    $sql .= "WHERE c.nom LIKE '%". db_escape($db, $client['nom']) ."%' 
    AND s.IdType ='" . db_escape($db, $client['IdType']) . "' AND c.active = '" . db_escape($db, $client['active']) . "' ";
  }elseif($nom_sent && $sport_sent){
    $sql .= "WHERE c.nom LIKE '%". db_escape($db, $client['nom']) ."%' AND s.IdType ='" . db_escape($db, $client['IdType']) . "'";
  }elseif($sport_sent && $active_sent){
    $sql .= "WHERE s.IdType ='" . db_escape($db, $client['IdType']) . "' AND c.active = '" . db_escape($db, $client['active']) . "'";
  }elseif($nom_sent && $active_sent){
    $sql .= "WHERE c.nom LIKE '%". db_escape($db, $client['nom']) ."%' AND c.active = '" . db_escape($db, $client['active']) . "'";
  }elseif($nom_sent){
    $sql .= "WHERE c.nom LIKE '%". db_escape($db, $client['nom']) ."%'";
  }elseif($sport_sent){
    $sql .= "WHERE s.IdType ='" . db_escape($db, $client['IdType']) . "'";
  }elseif($active_sent){
    $sql .= "WHERE c.active = '" . db_escape($db, $client['active']) . "'";
  }
  
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

//insert client
function insert_client($client, $files_infos){
  global $db;

  //check if error number 4 is set
  $img_is_not_set = false;
    if($files_infos['error'] == 4){
      //img file is blank
      $img_is_not_set =   true;
    }
  
  $errors = validate_client($client, $files_infos, ['img_is_not_set' => $img_is_not_set]);
  if(!empty($errors)) {
    return $errors;
  }

  //if img file is blank
  if($img_is_not_set){
    $sql = "INSERT INTO Clients (nom, prenom, Tel) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $client['nom']) . "', ";
    $sql .= "'" . db_escape($db, $client['prenom']) . "', ";
    $sql .= "'" . db_escape($db, $client['Tel']) . "'";
    $sql .= ")";
    //if not
  }else{
    $sql = "INSERT INTO Clients (nom, prenom, img, Tel) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $client['nom']) . "', ";
    $sql .= "'" . db_escape($db, $client['prenom']) . "', ";
    $sql .= "'" . db_escape($db, $files_infos['new_img_name']) . "', ";
    $sql .= "'" . db_escape($db, $client['Tel']) . "'";
    $sql .= ")";
  }
    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    } 
}

// insert SportClients
function insert_sport_client($client){
  global $db;
  
  $sql = "INSERT INTO SportClients (IdClient, IdSalle, IdType) ";
  $sql .= "VALUES (";
  $sql .= "'" . db_escape($db, $client['IdClient']) . "', ";
  $sql .= "'" . db_escape($db, $client['IdSalle']) . "', ";
  $sql .= "'" . db_escape($db, $client['IdType']) . "'";
  $sql .= ")";
  $result = mysqli_query($db, $sql);
  // For INSERT statements, $result is true/false
  if($result) {
    return true;
  } else {
    // INSERT failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  } 
}

//update client
function update_client($client, $files_infos){
  global $db;

  //check if error number 4 is set
  $img_is_not_set = false;
    if($files_infos['error'] == 4){
      //img file is blank
      $img_is_not_set =   true;
    }
  
  $errors = validate_client($client, $files_infos, ['img_is_not_set' => $img_is_not_set]);
  if(!empty($errors)) {
    return $errors;
  }


  $sql = "UPDATE Clients SET ";
  $sql .= "nom='" . db_escape($db, $client['nom']) . "', ";
  $sql .= "prenom='" . db_escape($db, $client['prenom']) . "', ";
  $sql .= "Tel='" . db_escape($db, $client['Tel']) . "', ";
  if(!$img_is_not_set){
    $sql .= "img='" . db_escape($db, $files_infos['new_img_name']) . "', ";
  }
  $sql .= "active='" . db_escape($db, $client['active']) . "' ";
  $sql .= "WHERE IdClient='" . db_escape($db, $client['IdClient']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

// update SportClients
function update_sport_client($client){
  global $db;

  $sql = "UPDATE SportClients SET ";
  $sql .= "IdSalle='" . db_escape($db, $client['IdSalle']) . "', ";
  $sql .= "IdType='" . db_escape($db, $client['IdType']) . "' ";
  $sql .= "WHERE IdClient='" . db_escape($db, $client['IdClient']) . "' ";
  $sql .= "LIMIT 1";
  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

//update last_pay for client
function update_last_pay($IdClient, $last_pay){
  global $db;

  $last_pay_sent = !is_blank($last_pay);
  
  $sql = "UPDATE Clients SET ";
  if($last_pay_sent){
    $sql .= "last_pay='" . db_escape($db, $last_pay) . "' ";
  }elseif(!$last_pay_sent){
    $sql .= "last_pay = NULL ";
  }
  $sql .= "WHERE IdClient='" . db_escape($db, $IdClient) . "' ";
  $sql .= "LIMIT 1";
  echo $sql;
  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

//delete client
function delete_client($client){
  global $db;

  $errors = validate_password_for_delete($client['password'], $client['user_id']);
  if(!empty($errors)) {
    return $errors;
  }

  $sql = "DELETE FROM Clients ";
  $sql .= "WHERE IdClient='" . db_escape($db, $client['IdClient']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

//validation for client
function validate_client($client, $files_infos, $options = []){
  $errors = [];

   // nom client 
   if(is_blank($client['nom'])) {
    $errors[] = "Le nom ne peut pas être vide.";
  } elseif(!has_length($client['nom'], ['min' => 2, 'max' => 255])) {
    $errors[] = "Nom doit comprendre entre 2 et 255 caractères.";
  }

   // prenom client 
   if(is_blank($client['prenom'])) {
    $errors[] = "Le prenom ne peut pas être vide.";
  } elseif(!has_length($client['prenom'], ['min' => 2, 'max' => 255])) {
    $errors[] = "Prenom doit comprendre entre 2 et 255 caractères.";
  }

   // tel client 
   if(is_blank($client['Tel'])) {
    $errors[] = "Tel ne peut pas être vide.";
  } elseif(!has_length($client['Tel'], ['min' => 2, 'max' => 255])) {
    $errors[] = "Tel doit comprendre entre 2 et 255 caractères.";
  }

  //get value of img file / true or false
  $require_img = $options['img_is_not_set'] ?? false;
  //do that just : img file is not blank
  if(!$require_img){
  //check file size
  if($files_infos['size'] > 200000){
  $errors[] = "image can't more than 2 Mo.";
  }

  //check file is valide
  $allowed_extensions = ['jpg', 'jpeg', 'png'];
  //get end of the array as string (extension)
  $extension = explode('.', $files_infos['img_name']);
  $img_extension = strtolower(end($extension));
  //checking if extensions in my_array_extensions
  if(!in_array($img_extension, $allowed_extensions)){
  $errors[] = "invalide image.";
  }
  }
  return $errors;
}

//payments table quries
//find  payments by id client
function find_payments_by_id_client($IdClient){
  global $db;

  $sql = "SELECT * FROM Payments ";
  $sql .= "WHERE IdClient='" . db_escape($db, $IdClient) . "' ";
  $sql .= "ORDER BY date_Payment DESC";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}
//find  payments by id 
function find_payment_by_id($IdPayment){
  global $db;

  $sql = "SELECT * FROM Payments ";
  $sql .= "WHERE IdPayment='" . db_escape($db, $IdPayment) . "' ";
  $sql .= "LIMIT 1";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $payment = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $payment; // returns an assoc. array
}

//find max dat_payment by idclient
function find_max_date_payment_by_id($IdClient){
  global $db;

  $sql = "SELECT MAX(date_Payment) as max_date_payment from payments ";
  $sql .= "WHERE IdClient='" . db_escape($db, $IdClient) . "' ";
  $sql .= "LIMIT 1";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $max_date_payment = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $max_date_payment['max_date_payment']; // returns date
}

//insert payment
function insert_payment($payments){
  global $db;

  $errors = validate_payments($payments);
    if(!empty($errors)) {
      return $errors;
    }

  
  $sql = "INSERT INTO Payments (date_Payment, IdClient, IdSalle, IdType, prix) ";
  $sql .= "VALUES (";
  $sql .= "'" . db_escape($db, $payments['date_Payment']) . "', ";
  $sql .= "'" . db_escape($db, $payments['IdClient']) . "', ";
  $sql .= "'" . db_escape($db, $payments['IdSalle']) . "', ";
  $sql .= "'" . db_escape($db, $payments['IdType']) . "', ";
  $sql .= "'" . db_escape($db, $payments['prix']) . "'";
  $sql .= ")";
  $result = mysqli_query($db, $sql);
  // For INSERT statements, $result is true/false
  if($result) {
    return true;
  } else {
    // INSERT failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  } 
}

// update payment
function update_payment($payments){
  global $db;

  $errors = validate_payments($payments);
    if(!empty($errors)) {
      return $errors;
    }

  $sql = "UPDATE Payments SET ";
  $sql .= "date_Payment='" . db_escape($db, $payments['date_Payment']) . "', ";
  $sql .= "prix='" . db_escape($db, $payments['prix']) . "' ";
  $sql .= "WHERE IdPayment='" . db_escape($db, $payments['IdPayment']) . "' ";
  $sql .= "LIMIT 1";
  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

//delete payment
function delete_payment($payments){
  global $db;

  $errors = validate_password_for_delete($payments['password'], $payments['user_id']);
  if(!empty($errors)) {
    return $errors;
  }

  $sql = "DELETE FROM Payments ";
  $sql .= "WHERE IdPayment='" . db_escape($db, $payments['IdPayment']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}


//validate function for payment
function validate_payments($payments){
  $errors = [];

   // date_Payment
   if(is_blank($payments['date_Payment'])) {
    $errors[] = "date_Payment ne peut pas être vide.";
  }

  // prix
  if(is_blank($payments['prix'])) {
    $errors[] = "Le prix ne peut pas être vide.";
  } elseif(!is_numeric($payments['prix'])) {
    $errors[] = "Prix doit être numérique.";
  }

  return $errors;
}








//validation for deleting any record
function validate_password_for_delete($password, $user_id) {
  $errors = [];

  $utilisateur = find_utilisateur_by_id($user_id);
  $password_hashed = $utilisateur['password'];
  if(!password_verify($password, $password_hashed)){
    $errors[] = "mot de passe incorrect.";
  }

  return $errors;
}

?>

