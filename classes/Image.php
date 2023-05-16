<?php
    use Cloudinary\Cloudinary;
    class Image {
        private $cloudinary;
        private $string;
        private $url = "https://res.cloudinary.com/de4kuz3ws/image/upload/v1/";

        private static function getConfig(){
            // get the config file
            return parse_ini_file(__DIR__ . "/config/config.ini");
        }
        private static function getRandomStringRamdomInt($length = 16){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        
        public function setup(){
            $config = self::getConfig();
            $this->cloudinary = new Cloudinary(
                [
                    'cloud' => [
                        'cloud_name' => $config["cloudname"],
                        'api_key'    => $config["apikey"],
                        'api_secret' => $config["apisecret"],
                    ],
                ]
            );
        }
        public function upload($assetspublic, $folder, $idtag){
            $randomstring = self::getRandomStringRamdomInt();
            $this->string = $randomstring;
            $this->cloudinary->uploadApi()->upload(
                $_FILES[$idtag]['tmp_name'],
                ['public_id' => "evestore/"."$assetspublic"."/".$folder."/".$randomstring], // optional
                // ['width' => 100, 'height' => 150, 'crop' => 'fill'] // cropping
            );
        }
        public function getString(){
            return $this->string;
        }
        public function getUrl(){
            return $this->url;
        }
    }