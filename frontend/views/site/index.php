
<?php
if(!empty($tree = unserialize($_COOKIE['tree'], ["allowed_classes" => false])))
    var_dump($tree);
?>

