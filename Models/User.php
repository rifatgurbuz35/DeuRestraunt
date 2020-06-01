<?php


class User extends ConnDb
{

    public function __construct()
    {
        parent::__construct();
    }

    public function kullaniciKontrol($name)
    {
        $response = [];
        //$qu=ConnDb::GETDB()->query("select * from customer where user_name='$name'",PDO::FETCH_ASSOC);
        $qu = ConnDb::GETDB()->query("select * from customer where user_name='$name'")->fetch(PDO::FETCH_ASSOC);
        if ($qu) {
            $response = [

                'status' => false,
                'message' => 'Mevcut'

            ];
        } else {
            $response = [

                'status' => true,
                'message' => 'Mevcut Degil'

            ];
            return json_encode($response);
        }
    }

    public function yeniKullanici($data)
    {

        $response = [];

        if (isset($data)) {

            $querry = ConnDb::GETDB()
                ->prepare("INSERT INTO `customer`( `name`, `surname`, `user_name`,`password`,`mail`,`is_active`,`phone`) 
                        VALUES  ('" . $data[0]['isim'] . "','" . $data[0]['soyIsim'] . "','" . $data[0]['kullanıcıAdı'] . "',
                        '" . $data[0]['sifre'] . "','" . $data[0]['email'] . "','1','" . $data[0]['telefon'] . "')");
            $qu = $querry->execute();


            if ($qu) {

                return $response = [

                    'status' => true,
                    'message' => 'Kayıt Başarılı',
                    'code' => 1

                ];
            } else {
                return $response = [

                    'status' => true,
                    'message' => 'Kayıt Başarısz',
                    'code' => 2

                ];
            }
        } else {
            return $response = [

                'status' => false,
                'message' => 'Post işlemi Başarsız'

            ];
        }

    }

    public function loginKontrol($name, $pass)
    {

        $response = [];
        $qu = ConnDb::GETDB()->query("select id from customer where user_name='$name' and password='$pass'")->fetch(PDO::FETCH_ASSOC);
        if ($qu) {
            $id = intval($qu['id']);
            session_start();
            $_SESSION['kullanıcıId'] = $id;
            $response = [

                'status' => true,
                'message' => 'Mevcut'

            ];

            echo  json_encode(['status'=>true]);
        } else {
            $response = [

                'status' => false,
                'message' => 'Mevcut Degil'

            ];
            return json_encode($response);
        }
    }

}
