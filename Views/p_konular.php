<?php require HEADER; ?>

<div class="" style="padding:10px; background-color:#eaeaea; ">
    <h2> kafam</h2>
    <h1>taşşak gibi</h1>
</div>

<?php

if (is_array($data) || is_object($data))
{
    foreach ($data as $key)
    {
        echo '
        <div class="" style="padding:10px; background-color:#eaeaea; ">
        <h2>'.$key['konu_adi'].'</h2>
        <p><a href="'.PATH.'konu_goruntule'.'/'.$key['id'].'">Görüntüle</a></p>
        <p><a href="'.PATH.'post/konuSil'.'/'.$key['id'].'">Konu Sil</a></p>
        </div>';
    }
}
?>
<?php require FOOTER?>

