<?php      
    session_start();
    session_destroy(); //刪除全部的session

    echo json_encode(array());
       
?>