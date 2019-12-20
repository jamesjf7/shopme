<?php
    session_start();
    date_default_timezone_set('Asia/Jakarta');
    $conn = new mysqli('localhost','root','','proyek_aplin');
    function insert($table, $data){
        require_once 'conn.php';
        $col = []; $row = [];
        foreach($data as $key => $value){
            $col[] = $key;
            $row[] = $value;
        }
        $field = join(',',$col);
        $value = join(',',$row);
        $GLOBALS['conn']->query("INSERT INTO $table($field) VALUES ($value)");
        $return_id = $GLOBALS['conn']->insert_id;
        return $return_id;
    }
?>