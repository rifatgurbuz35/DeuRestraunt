<?php require HEADER; ?>

<div class="" style="padding:10px; background-color:#eaeaea; ">
    <h2> kafam</h2>
    <h1>taşşak gibi</h1>
</div>

<?php

if(isset($data) && $data!=false){
    echo '<h2>'.$data['konu_adi'].'</h2>';
    echo '<p>İçereik</p>';
    echo '<h2>'.$data['konu_icerik'].'</h2>';
}


?>
<?php require FOOTER?>

