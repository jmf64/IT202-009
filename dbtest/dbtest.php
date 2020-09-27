<?php
require("db.php");
$db = getDB();
if(isset($db)){
        $query = file_get_contents("create_table_users.sql");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $e = $stmt->errorInfo();
        if($e[0] != '00000'){
                echo "Query error: " . var_export($e, true);       
        }
        else{
                echo "table created successfully";
        }
        
}
else{
        echo "there may be a problem with our connection details";
}
?>
