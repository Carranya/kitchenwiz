<div class='absolute top-0 right-0 bg-red-500'>
    <a href='/phpmyadmin' target='_blank'>MySQL tables</a><br>
    <a href='create.php' target='_blank'>Update tables</a><br>
    <a href='test.php' target='_blank'>Test page</a>
</div>

<?php
    function ff($value){
        echo "<pre>";
            var_dump($value);
        echo "</pre>";
        die();
    }
?>