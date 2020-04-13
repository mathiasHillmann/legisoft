<?php
    define('DB_SERVER', 'localhost');
    define('DB_USUARIO', 'root');
    define('DB_SENHA', '');
    define('DB_DATABASE', 'projeto');
    $db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
    if (mysqli_connect_errno()) {
    	echo "<script>alert(Erro ao conectar ao banco de dados: " . mysqli_connect_error() ."</script>";
    }
?>