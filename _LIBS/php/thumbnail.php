<?php
##############################################
# Modificada  adaptada por
# Omar Alejan dro Santohyo Cota
# 18 diciembre 2015
## encontrada en http://blog.freshware.es/como-generar-thumbnails-con-php/ 
##############################################
# Shiege Iseng Resize Class
# 11 March 2003
# shiegege_at_yahoo.com
# View Demo :
#   http://shiege.com/scripts/thumbnail/
/*############################################
Sample :
$thumb=new thumbnail("./shiegege.jpg");			// generate image_file, set filename to resize
$thumb->size_width(100);				// set width for thumbnail, or
$thumb->size_height(300);				// set height for thumbnail, or
$thumb->size_auto(200);					// set the biggest width or height for thumbnail
$thumb->jpeg_quality(75);				// [OPTIONAL] set quality for jpeg only (0 - 100) (worst - best), default = 75
$thumb->show();						// show your thumbnail
$thumb->save("./huhu.jpg");				// save your thumbnail to file
----------------------------------------------
Note :
- GD must Enabled
- Autodetect file extension (.jpg/jpeg, .png, .gif, .wbmp)
  but some server can't generate .gif / .wbmp file types
- If your GD not support 'ImageCreateTrueColor' function,
  change one line from 'ImageCreateTrueColor' to 'ImageCreate'
  (the position in 'show' and 'save' function)
*/
class thumbnail
{
	private $img;
	//default quality jpeg
	private $quality=75;
	//init
	function __construct($ruta){
		if (file_exists($ruta)) {

			$this -> img['url'] = $ruta;
			$this -> img['format'] = strtoupper($this ->extension($this -> img['url']));

			if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
				//JPEG
				$this->img["format"]="JPEG";
				$this->img["src"] = ImageCreateFromJPEG ($this -> img['url']);

			} elseif ($this->img["format"]=="PNG") {
				//PNG
				$this->img["format"]="PNG";
				$this->img["src"] = ImageCreateFromPNG ($this -> img['url']);
			} elseif ($this->img["format"]=="GIF") {
				//GIF
				$this->img["format"]="GIF";
				$this->img["src"] = ImageCreateFromGIF ($this -> img['url']);
			} elseif ($this->img["format"]=="WBMP") {
				//WBMP
				$this->img["format"]="WBMP";
				$this->img["src"] = ImageCreateFromWBMP ($this -> img['url']);
			} else {
				//DEFAULT
				echo "Not Supported File";
				exit();
			}

			@$this->img["lebar"] = imagesx($this->img["src"]);
			@$this->img["tinggi"] = imagesy($this->img["src"]);
			
			} else {
			    echo "El fichero $img no existe [thumbnail.php]";
			}		
	}
	public function __set($var, $valor){
		// convierte a minúsculas toda una cadena la función strtolower
		$temporal = strtolower($var);
		// Verifica que la propiedad exista, en este caso el nombre es la cadena en "$temporal"
		if (property_exists('thumbnail',$temporal)){
			$this->$temporal = $valor;
		}
		else{
			echo $var . " No existe.";
		}
	}
		 
	public function __get($var){
		$temporal = strtolower($var);
		// Verifica que exista
		 
		if (property_exists('thumbnail', $temporal))
		{
			return $this->$temporal;
		}
		 
		// Retorna nulo si no existe
		return NULL;
	}

	function show(){
		//$this->img["quality"]= $this->quality;
		
		header('Content-Type: image/'.$this->img["format"]);

		$this->img["des"] = ImageCreateTrueColor($this->img["lebar_thumb"],$this->img["tinggi_thumb"]);
		imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["lebar_thumb"], $this->img["tinggi_thumb"], $this->img["lebar"], $this->img["tinggi"]);

		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			//imageJPEG($this->img["src"]);
			imageJPEG($this->img["des"],NULL,$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"]);
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"]);
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"]);
		}
		imagedestroy($this->img["des"]);
		imagedestroy($this->img["des"]);
	}

	function getImg(){
echo "string";
		$this->img["des"] = ImageCreateTrueColor($this->img["lebar_thumb"],$this->img["tinggi_thumb"]);
		imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["lebar_thumb"], $this->img["tinggi_thumb"], $this->img["lebar"], $this->img["tinggi"]);
ob_start();
		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			//imageJPEG($this->img["src"]);
			imageJPEG($this->img["des"],NULL,$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"]);
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"]);
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"]);
		}
$imgdata = ob_get_contents(); 
ob_end_clean();
		imagedestroy($this->img["des"]);
		imagedestroy($this->img["des"]);
return bin2hex($imgdata);
	}

	function size_height($size=100){
		//height
    	$this->img["tinggi_thumb"]=$size;
    	@$this->img["lebar_thumb"] = ($this->img["tinggi_thumb"]/$this->img["tinggi"])*$this->img["lebar"];
	}

	function size_width($size=100){
		//width
		$this->img["lebar_thumb"]=$size;
    	@$this->img["tinggi_thumb"] = ($this->img["lebar_thumb"]/$this->img["lebar"])*$this->img["tinggi"];
	}

	function size_auto($size=100){		
		//size
		if ($this->img["lebar"]>=$this->img["tinggi"]) {
    		$this->img["lebar_thumb"]=$size;
    		@$this->img["tinggi_thumb"] = ($this->img["lebar_thumb"]/$this->img["lebar"])*$this->img["tinggi"];
		} else {
	    	$this->img["tinggi_thumb"]=$size;
    		@$this->img["lebar_thumb"] = ($this->img["tinggi_thumb"]/$this->img["tinggi"])*$this->img["lebar"];
 		}
	}
	function quality($quality=75){
		//jpeg quality
		$this->img["quality"]=$quality;
	}
	function extension($file_name){
		$tmp = explode('.', $file_name);
		$file_extension = end($tmp);
        return $file_extension;
	}
	function save($save=""){
		@Header("Content-Type: image/".$this->img["format"]);

		//save thumb
		if (empty($save)) $save=strtolower("./thumb.".$this->img["format"]);
		/* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
		$this->img["des"] = ImageCreateTrueColor($this->img["lebar_thumb"],$this->img["tinggi_thumb"]);
    		@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["lebar_thumb"], $this->img["tinggi_thumb"], $this->img["lebar"], $this->img["tinggi"]);

		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			imagejpeg($this->img["des"],"$save",$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"],"$save");
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"],"$save");
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"],"$save");
		}
	}


	function test($filename){

		$percent = 0.5;
		// Content type
		@Header('Content-type: image/jpeg');

		// Get new sizes
		list($width, $height) = getimagesize($filename);
		$newwidth = $width * $percent;
		$newheight = $height * $percent;

		// Load
		$thumb = imagecreatetruecolor($newwidth, $newheight);
		$source = imagecreatefromjpeg($filename);

		// Resize
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		// Output
		imagejpeg($thumb);
	}



	
}
/*\
$imagen = NEW thumbnail('test.jpg');
$imagen -> size_auto(500);
$imagen -> quality(100);
$imagen -> show();
*/
?>
