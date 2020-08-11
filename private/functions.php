<?php

function url_for($script_path) {
  // add the leading '/' if not present
  if($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

function redirect_to($location) {
  header("Location: " . $location);
  exit;
}

function u($string="") {
  return urlencode($string);
}

function raw_u($string="") {
  return rawurlencode($string);
}

function h($string="") {
  return htmlspecialchars($string);
}

function error_404() {
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit();
}

function error_500() {
  header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
  exit();
}


function is_post_request() {
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function display_errors($errors=array()) {
  $output = '';
  if(!empty($errors)) {
    foreach($errors as $error) {
    $output .= "<div class=\"alert alert-danger\" role=\"alert\">";
      $output .= h($error);
    $output .= "</div>";
    } 
  }
  return $output;
}


//functions for message
function get_and_clear_session_message() {
  if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    return $msg;
  }
}

function display_session_message() {
  $msg = get_and_clear_session_message();
  if(!is_blank($msg)) {
    return '<div class="alert alert-success role="alert"> ' . h($msg) . ' </div>';   
  }
}

//functions for message_two
function get_and_clear_session_message_two() {
  if(isset($_SESSION['message_two']) && $_SESSION['message_two'] != '') {
    $msg = $_SESSION['message_two'];
    unset($_SESSION['message_two']);
    return $msg;
  }
}

function display_session_message_two() {
  $msg = get_and_clear_session_message_two();
  if(!is_blank($msg)) {
    return '<div class="alert alert-success role="alert"> ' . h($msg) . ' </div>';   
  }
}

//functions for message_info
function get_and_clear_session_message_info() {
  if(isset($_SESSION['message_info']) && $_SESSION['message_info'] != '') {
    $msg = $_SESSION['message_info'];
    unset($_SESSION['message_info']);
    return $msg;
  }
}

function display_session_message_info() {
  $msg = get_and_clear_session_message_info();
  if(!is_blank($msg)) {
    return '<div class="alert alert-warning role="alert"> ' . h($msg) . ' </div>';   
  }
}


//function : get extension
function get_extenssion($img_name){
  $extension = explode('.', $img_name);
  $img_extension = strtolower(end($extension));
  return $img_extension;
 }


?>
