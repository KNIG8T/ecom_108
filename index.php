<?php 
    require_once "dbconn.php";
    $sql = "select * from item";
    $stmd = $conn -> query($sql);
    $stmd -> execute();
    $items = $stmd -> fetchAll();
    foreach($items as $item) {
        // echo $item ['iname'] ."<br>";
        // echo $item ['price'] ."<br>";
        // echo $item ['description'] ."<br>";
        // echo $item ['quantity'];
        // echo $item ['img_path'];
        // echo "<img src = $item -> img_path>";

        echo $item['iname'];
        echo $item['price'];
        echo "<img src=$item[img_path]>";
    }
?>