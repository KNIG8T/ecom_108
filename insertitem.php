<?php
    require_once "dbconn.php";
    //session create
    if(!isset($_SESSION[""])) {
        session_start();
    }

    $sql ="select * from category";
    $stmt = $conn ->prepare($sql);
    $stmt->execute();
    $categories = $stmt -> fetchAll();

    // after clicking insert button
    if(isset($_POST["insertItem"])) {
        $itemName = $_POST['itemName'];
        $price = $_POST['price'];
        $quantity = $_POST['price'];
        $description = $_POST['description'];
        $categorie = $_POST['category'];
        $fileName = $_FILES['img']['name'];
        $filePath = "images/" .$fileName; //prepare server location

        //store image in server (local machine hardisk)
        $status = move_uploaded_file($_FILES["img"]["tmp_name"], $filePath);
        if ($status) {
            $sql = "insert into item values(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([null, $itemName, $price, $description, $quantity, $filePath, $categorie]);
            $lastId = $conn->lastInsertId();

            if ($status) {
                $_SESSION['insertSuccess'] = "Item with Id $lastId has been inserted successfully";
                header("Location:viewItem.php");
        }
    }

        // echo $itemName;
        // echo "<br>".$price;
        // echo "$fileName";
    }

    // foreach($categories as $category) {
    //     echo $category ["cid"]."<br>";
    //     echo $category ["cname"];
    //     echo "<br><br>";
    // }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <?php 
                    require_once"navbar.php"; 
                ?>
            </div>
            <div class="row">
                <div class="col-md-3">Column 3</div>
                <div class="col-md-9 mx-auto">
                    <form class="form mt-2 pt-2" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <fieldset>
                            <legend>Insert Item</legend>
                            <div class="mb-2">
                                <label for="itemName" class="form-label">Item Name</label>
                                <input type="text" class="form-control" name="itemName">
                            </div>
                            <div class="mb-2">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" name="price">
                            </div>
                            <div class="mb-2">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-2">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">Select Category</option>
                                    <?php if(isset($categories)) {
                                        foreach($categories as $category) {
                                            echo "<option value = $category[cid]> $category[cname]</option>";
                                        }
                                    }
                                    ?>;
                                </select>
                            </div>
                            <div class="mb2">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity">
                            </div>
                            
                            <div class="mb-2">
                                <label for="img" class="form-label">Choose Item Image</label>
                                <input type="file" class="form-control" name="img">
                            </div>

                            <button type="submit" class="btn btn-primary" name="insertItem">Insert Item</button>
                            
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>