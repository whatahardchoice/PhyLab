<?php
    move_uploaded_file($_FILES["file"]["tmp_name"],"flag/" . $_FILES["file"]["name"]);
?>
