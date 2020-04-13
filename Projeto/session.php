<?php
   include('config.php');
   session_start();
   
   $usuario_check = $_SESSION['usuario'];
   
   $sql = mysqli_query($db,"select usuario from usuarios where usuario = '$usuario_check' ");
   
   $coluna = mysqli_fetch_array($sql,MYSQLI_ASSOC);
   
   $login_session = $coluna['usuario'];
   
   if(!isset($_SESSION['logado'])){
      header("location:login.php");
      die();
   }
?>