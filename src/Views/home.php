<?php
    $title = 'Home';
    ob_start();
?>


<div class="row justify-content-center">
    <div class="col col-8">
        <div class="mt-4">

        </div>
        <div class="mt-2" id="posts-container">
<!--                    Posts-->
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
include 'layout.php';
?>
