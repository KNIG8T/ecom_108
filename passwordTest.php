<?php
    $hashcode = password_hash("Abc123!@#", PASSWORD_BCRYPT);
    echo $hashcode;
    echo "<br>". strlen($hashcode);

?>