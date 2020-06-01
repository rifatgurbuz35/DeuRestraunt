<?php


class Siparis extends ConnDb
{
    public function __construct()
    {
        parent::__construct();
    }

    public function siparisOlustur($data)
    {
        /* Burada ilk önce order tablosuna sessiondaki kullanıcı Id ile kayıt var mı kontrolü yapılacak
           eğer varsa order tablosundan ilgii kaydın id si alınarak order_cart'a bu id ile kayıt atılacak..
         * Kayıt yoksa ilk önce order tablosuna kayıt atılacak kayıt atıldıktan id si alınıp order_cart'a  kayıt atılacak.
         * Burada order ile order_cart arasında 1-n ilişkisi vardır.
         */
        if (isset($_SESSION['kullanıcıId'])) {
            $user_id = $_SESSION['kullanıcıId'];

            if (isset($data)) {

                //burada user id kontrolü yap
                $orderKontrol = ConnDb::GETDB()->query("select * from deu_restaurant.order where user_id='$user_id' and siparis_tamam=0")->fetch(PDO::FETCH_ASSOC);
                if ($orderKontrol) {
                    $id = $orderKontrol['id'];
                    foreach ($data as $deger) {
                        $this->orderCartKaydet($deger, $id, $user_id);
                    }

                } else {

                    $querry = ConnDb::GETDB()
                        ->prepare("INSERT INTO `order`( `user_id`, `toplam_tutar`, `rezarvasyon_tarihi`,`gelis_zamani`,`siparis_tamam`) 
                        VALUES  ('" . $user_id . "','0','2020-05-31 00:00:00','2020-05-31 00:00:00','0')");
                    $qu = $querry->execute();
                    $id = ConnDb::GETDB()->lastInsertId();
                    foreach ($data as $deger) {
                        $this->orderCartKaydet($deger, $id, $user_id);
                    }
                }

            }
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }

    }

    public function orderCartKaydet($deger, $id, $user_id)
    {

        $menu_id = $deger['urun_id'];
        $count = $deger['adet'];
        $tutar = $deger['fiyat'];
        $kategori_id = $deger['kahvalti'];
        $querryCart = ConnDb::GETDB()
            ->prepare("INSERT INTO `order_cart`( `user_id`, `menu_id`, `count`,`tutar`,`durum`,`order_id`,`kategori_id`) 
                        VALUES  ('" . $user_id . "','" . $menu_id . "','" . $count . "','" . $tutar . "','0','" . $id . "','" . $kategori_id . "')");
        $querryCart->execute();

    }

    public function siparisCek()
    {

        if (isset($_SESSION['kullanıcıId'])) {
            $user_id = $_SESSION['kullanıcıId'];
            $orderId = 0;
            $siparisler = [];
            $orderIdQuery = ConnDb::GETDB()->query("select * from deu_restaurant.order where user_id='$user_id' and siparis_tamam=0")->fetch(PDO::FETCH_ASSOC);
            if ($orderIdQuery) {
                $orderId = $orderIdQuery["id"];
                // $orders = ConnDb::GETDB()->query("select * from order_cart where order_id='$orderId' ")->fetchAll(PDO::FETCH_ASSOC);
                $distinct = ConnDb::GETDB()->query("select distinct menu_id from order_cart where user_id='$user_id'; ")->fetchAll(PDO::FETCH_ASSOC);
                $toplam=0;
                foreach ($distinct as $deger) {

                    $menu_id = $deger['menu_id'];
                    $siparis = ConnDb::GETDB()->query("select cart.*,menu.id menu_id,menu.name kategori from order_cart cart inner join menu on cart.kategori_id=menu.id where menu_id='$menu_id' limit 1 ;")->fetch(PDO::FETCH_ASSOC);
                    $adet = ConnDb::GETDB()->query("select sum(tutar) tutar, sum(count) adet from order_cart where menu_id='$menu_id' ;")->fetch(PDO::FETCH_ASSOC);
                    $total = ConnDb::GETDB()->query("select tutar * (select  sum(count) adet  from order_cart where menu_id='$menu_id') tutar from order_cart where menu_id='$menu_id' limit 1;")->fetch(PDO::FETCH_ASSOC);
                    $urun = ConnDb::GETDB()->query("select * from items where id='$menu_id' limit 1 ;")->fetch(PDO::FETCH_ASSOC);
                    $toplam+=$total["tutar"];
                    $siparisler[] = array(
                        "user_id" => $siparis["user_id"],
                        "menu_id" => $menu_id,
                        "count" => $adet["adet"],
                        "tutar" => $siparis["tutar"],
                        "durum" => $siparis["durum"],
                        "order_id" => $siparis["order_id"],
                        "kategori_id" => $siparis["kategori_id"],
                        "toplam_tutar" => $total["tutar"],
                        "urun_adi" => $urun["item_name"],
                        "urun_id" => $urun["id"],
                        "content" => $urun["content"],
                        "image_url" => $urun["image_url"],
                        "kategori"=>$siparis["kategori"],

                    );

                }
                $_SESSION['toplam']=$toplam;
                return $siparisler;
            }


        }
    }

    public  function siparisUpdate($data,$tutar){
        if (isset($_SESSION['kullanıcıId'])) {
            $response=[];
            if(isset($data)){
                foreach ($data as $key){

                }
            }

        }
    }

}
