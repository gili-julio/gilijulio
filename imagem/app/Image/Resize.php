<?php

namespace App\Image;

class Resize{

    /**
     * Imagem (GD)
     * @var
     */
    private $image;

    /**
     * Tipo da imagem
     * @var string
     */
    private $type;

    /**
     * Método responsável por carregar os dados da classe 
     * @param string $file
     */
    public function __construct($file){
        
        
        //INFO
        $info = pathinfo($file);
        $this->type = $info['extension'] == 'jpg' ? 'jpeg' : $info['extension'];
        //IMAGEM
        switch($this->type){
            case 'jpeg':
                $this->image = imagecreatefromjpeg($file);
                break;
                
            case 'png':
                $filepath = $info['dirname']."/".$info['filename'];
                $filepathcompleto = $filepath.'.'.$this->type;
                $image = imagecreatefrompng($filepathcompleto);
                $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
                imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                imagealphablending($bg, TRUE);
                imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                imagedestroy($image);
                $quality = 50; // 0 = low / smaller file, 100 = better / bigger file 
                imagejpeg($bg, $filepath . ".jpeg", $quality);
                imagedestroy($bg);
                $this->image = imagecreatefromjpeg($filepath . ".jpeg");
                $this->type = 'jpeg';
                break;

            case 'bmp':
                $this->image = imagecreatefrombmp($file);
                break;

            case 'gif':
                $this->image = imagecreatefromgif($file);
                break;

            case 'webp':
                $this->image = imagecreatefromwebp($file);
                break;
        }
    }

    /**
     * Método responsável por redimensionar a imagem
     * @param integer $newWidth
     * @param integer $newHeight
     */
    public function resize($newWidth,$newHeight){
        $this->image = imagescale($this->image,$newWidth,$newHeight);
    }

    /**
     * Método responsável por imprimir a imagem na tela
     * @param integer $quality (0-100)
     */
    public function print($quality = 50){
        header('Content-type: image/'.$this->type);
        $this->output(null,$quality);
        exit;
    }

    /**
     * Método responsável por salvar a imagem no disco
     * @param string $localFile
     * @param integer $quality
     */
    public function save($localFile,$quality = 50){
        $this->output($localFile,$quality);
    }

    /**
     * Método responsável por executar a saida da imagem
     * @param string $localFile
     * @param integer $quality
     */
    private function output($localFile, $quality = 50){
        
        switch($this->type){
            case 'jpeg':
                imagejpeg(imagerotate($this->image, 270, imageColorAllocateAlpha($this->image, 0, 0, 0, 127)),$localFile,$quality);
                break;
                
            case 'png':
                imagepng($this->image,$localFile,$quality);
                break;

            case 'bmp':
                imagewbmp($this->image,$localFile,$quality);
                break;

            case 'gif':
                imagegif($this->image,$localFile,$quality);
                break;

            case 'webp':
                imagewebp($this->image,$localFile,$quality);
                break;

        }


    }


}