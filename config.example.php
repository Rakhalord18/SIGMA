<?php

    $host_db = "localhost";
    $user_db = "root";
    $pass_db = "";
    $db_name = "sigma_db";

    $koneksi = mysqli_connect($host_db, $user_db, $pass_db, $db_name);
    
    if(!$koneksi)
    {
        echo "Koneksi tidak berhasil". mysqli_connect_error();
    }

function base_url($url = null)
{
    $base_url = 'http://localhost/SigmaDone'; // Sesuaikan dengan nama folder di localhost kamu
    if($url != null){
        return $base_url . '/' . $url;
    }else{
        return $base_url;
    }
}

?>
