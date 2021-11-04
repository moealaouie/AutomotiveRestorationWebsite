<?php
 /*	
        Software Engineering: session.php
     	Start date: 9.22.19
        Authors: Amber Sakkinen, Fatmeh Mazloum, Bruce Stancato, Tim Somes
        Last edited: 9.22.19
        https://cislinux.hfcc.edu/~bwstancato/CIS294/Test_Page.php
*/

// Starting session
session_start();
require_once ("config.php");
spl_autoload_register(function ($class_name) {
  $class_path = str_replace('\\', '/', $class_name);
  if (file_exists(__DIR__ . '/src/' . $class_path . '.php')) {
    include __DIR__ . '/src/' . $class_path . '.php';
  }
});
$database = Database::create();
 

 

// Junk Drawer
//$values = array('user' => $user, 'pass' => $pass, 'request' => 'login');
//user_info =$database-> processRequest($values);

