
<?php


class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function yeniKullanici(){
        $response=[];
        if(isset($_POST['dataKullanici'])){
            $data=$_POST['dataKullanici'];
            include "Models/User.php";
            $user = new User();
            $kontrol = $user->kullaniciKontrol($data[0]['kullanıcıAdı']);
            $sonuc=json_decode($kontrol,true);
            if($sonuc){
               $kayıt=$user->yeniKullanici($data);
               return $kayıt;
            }
            else{
                $response = [

                    'status' => true,
                    'message' => 'Bu kullanici adi ile Kayit mevcuttur.',
                    'code' => 3

                ];
                return json_encode($response);


            }
        }else{
            $response = [

                'status' => true,
                'message' => 'Kayıt Başarısız',
                'code' => 4

            ];
            return json_encode($response);
        }

    }

    public  function loginKontrol(){

        $response=[];
        if(isset($_POST['dataLogin'])){
            $data=$_POST['dataLogin'];
            include "Models/User.php";
            $user = new User();
            $kontrol = $user->loginKontrol($data[0]['kullaniciAdi'],$data[0]['pass']);
            $sonuc=json_decode($kontrol,true);
            if($sonuc){

              return  $sonuc;
            }
            else{
                $response = [

                    'status' => false,
                    'message' => 'Kullanici Bulunamadi'


                ];
                return json_encode($response);


            }
        }else{
            $response = [

                'status' => false,
                'message' => 'Kullanici Bulunamadi'

            ];
            return json_encode($response);
        }

    }

    public function existMethod($str){

        if(method_exists($this,$str)){
            return true;
        }
        else{
            return false;
        }
    }
}
