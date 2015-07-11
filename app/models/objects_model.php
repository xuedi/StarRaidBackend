<?php
namespace Pedetes;

use \PDO;

class blog_model extends \Pedetes\model {




	function __construct($ctn) {
		parent::__construct($ctn);
		$this->pebug->log( "blog_model::__construct()" );
	}





	public function getList($lang) {
		$retVal = array();
		$para = array('language' => $lang);
		$sql = "SELECT * FROM blog b ORDER BY created_at DESC";
		$main = $this->db->select($sql, $para);
		foreach($main as $value) {
			$retVal[$value['id']] = array(
				'id' => $value['id'],
				'url' => $value['url'],
				'created_at' => $value['created_at']
				);
		}
		// add length of contents
		$main = $this->db->select("SELECT *, char_length(content) as res FROM blog_translation ");
		foreach($main as $value) {
			$retVal[$value['blog_id']][$value['language']] = $value['res'];
		}

		return $retVal;
	}





	public function getListFrontend($lang) {

		// get basics
		$retVal = array();
		$main = $this->db->select("SELECT bt.blog_id, bt.title, bt.content, b.images, b.created_at FROM blog_translation bt LEFT JOIN blog b ON b.id = bt.blog_id WHERE language = '$lang' ORDER BY b.created_at DESC ");
		foreach($main as $value) {
			$image = array();
			if(!empty($value['images'])) {
				foreach(unserialize($value['images']) as $entry) {
					if(strpos($entry, '_150.')) {
						$image[] = $entry;
					}
				}
			}
			shuffle($image);
			$image = $image[0];
			if($value['content']) {
				$id = $value['blog_id'];
				$title = $value['title'];
				$content = $value['content'];
				$created_at = $value['created_at'];
				if(mb_strlen($content)>150) {
					$content = mb_substr($content, 0, 150)." <a href=''>...</a>";
				}
				$retVal[] = array('id'=>$id,'title'=>$title,'content'=>$content,'image'=>$image,'created_at'=>$created_at);
			}
		}
		return $retVal;
	}





	public function load($blog_id, $language) {
		if(!$language) $language = 'en'; //TODO: Broken

		// get basics
		$retVal = array();
		$para = array('blog_id' => $blog_id);
		$sql = "SELECT * FROM blog WHERE id = :blog_id ";
		$main = $this->db->select($sql, $para);
		$images = null;
		foreach($main as $value) {
			$images = $value['images'];
			if(filter_var("http://".$value['url'], FILTER_VALIDATE_URL) !== false) $validUrl = true;
			else $validUrl = false;
			$retVal = array(
				'id' => $value['id'],
				'url' => $value['url'],
				'validUrl' => $validUrl,
				'created_at' => $value['created_at']
				);
		}
		// fetch extended language setting and fill up translations with dummies
		$i18n = $this->loadModel('i18n');
		$temp = $i18n->getLanguages($language);
		foreach($temp as $value) {
			$langNames[$value['language']] = $value['native'];
			$retVal['lang'][$value['language']] = array(
				'lang' => $value['language'],
				'langName' => $value['native'],
				'title' => '',
				'content' => ''
				);
		}
		// update given translations
		$sql = "SELECT * FROM blog_translation WHERE blog_id = :blog_id ";
		$main = $this->db->select($sql, $para);
		foreach($main as $value) {
			$lang = $value['language'];
			$retVal['lang'][$lang] = array(
				'lang' => $lang,
				'langName' => $langNames[$lang],
				'title' => $value['title'],
				'content' => $value['content']
				);
		}
		// get images
		if($images) {
			$images = unserialize($images);
			foreach($images as $value) {
				$hash = explode('_', $value)[1];
				$type = explode('.', $value)[1];
				if(strpos($value, "_1000.")) $retVal['images_1000'][$hash.'_'.$type] = $value;
				elseif(strpos($value, "_150.")) $retVal['images_150'][$hash.'_'.$type] = $value;
				else $retVal['images'][$hash.'_'.$type] = $value;
			}
		}
		return $retVal;
	}





	public function loadFrontend($blog_id, $language) {
		$retVal = array();
		$temp = $this->load($blog_id, $language);
		$retVal['id'] = $temp['id'];
		$retVal['url'] = $temp['url'];
		$retVal['created_at'] = $temp['created_at'];
		$retVal['lang'] = $temp['lang'][$language]['lang'];
		$retVal['langName'] = $temp['lang'][$language]['langName'];
		$retVal['title'] = $temp['lang'][$language]['title'];
		$retVal['content'] = $temp['lang'][$language]['content'];
		$images = array();
		if(!empty($temp['images'])) {
			foreach($temp['images'] as $key => $value) {
				$tmp = explode('_', $key);
				$images[$tmp[0]] = $tmp[1];
			}
		}
		$retVal['images'] = $images;
		return $retVal;
	}





	public function create() {
		$retVal = $this->getNewId();
		if(!$retVal) {
			$this->db->select("INSERT INTO blog (url) VALUES ('NEW') ");
			$retVal = $this->getNewId();
		}
		return $retVal;
	}





	public function save() {
		$data = array();
		foreach($_REQUEST as $key => $value) {
			if(substr($key,0,5)=="edit_") {
				$key = substr($key,5);
				$data[$key] = $value;
			}
		}
		if(is_numeric($data['id'])) {
			$id = $data['id'];

			// get languages
			$i18n = $this->loadModel('i18n');
			$temp = $i18n->getLanguages('en');
			$languages = array();
			foreach ($temp as $key => $value) {
				$languages[$value['language']] = $value['language'];
			}

			// update host
			$this->db->select("UPDATE blog SET url = :url, created_at = :created_at WHERE id = :id ", array('url' => $data['url'], 'created_at' => $data['created_at'], 'id' => $id));

			// gather translations
			$trans = array();
			foreach($data as $key => $value) {
				$tmpKey = explode('_', $key);
				if(in_array($tmpKey[1], $languages)) {
					$trans[$tmpKey[1]][$tmpKey[0]] = $data[$key];
				}
			}

			// update translations
			foreach($trans as $key => $value) {
				$sql = "REPLACE INTO blog_translation (blog_id, language, title, content) VALUES (:blog_id, :language, :title, :content)";
				$para = array('title'=>$value['title'],'content'=>$value['content'],'blog_id'=>$id,'language'=>$key);
				$this->db->select($sql,$para);
			}

			// add images
			$images = $this->_saveFile($id);
			$this->db->select("UPDATE blog SET images = :images WHERE id = :id ", array('images'=>$images,'id'=>$id));

			return $id;
		}
	}





	public function deleteImage() {
		$id = $this->request->get('id', 'NUMBER');
		$para = $this->request->get('hash', 'TEXT');
		$hash = explode('_', $para)[0];
		$type = explode('_', $para)[1];
		if(strLen($hash)==32 && is_numeric($id)) {
			$base = $this->ctn['config']['path']['base'];
			$path = $base."web/images/blog/";
			$filename = $path.$id.'_'.$hash;
			if(file_exists($filename.'_max.'.$type)) unlink($filename.'_max.'.$type);
			if(file_exists($filename.'_150.'.$type)) unlink($filename.'_150.'.$type);
			if(file_exists($filename.'_1000.'.$type)) unlink($filename.'_1000.'.$type);
			$images = $this->_saveFile($id);
			$this->db->select("UPDATE blog SET images = :images WHERE id = :id ", array('images'=>$images,'id'=>$id));
		}
		return $id;
	}



	/////////
	private function getNewId() {
		$main = $this->db->selectOne("SELECT id FROM blog WHERE url = 'NEW' ");
		return $main['id'];
	}
	private function _saveFile($id) {
		$types = array('image/png'=>'png','image/jpeg'=>'jpg','image/gif'=>'gif','image/bmp'=>'bmp','image/tiff'=>'tiff');
		$base = $this->ctn['config']['path']['base'];
		$temp = $_FILES['edit_image']['tmp_name'];
		$path = $base."web/images/blog/";
		if($id && $path && $temp) {
			$hash = hash_file('md5', $temp);
			$type = $types[getimagesize($temp)['mime']];
			$filename = $path.$id.'_'.$hash.'_max.'.$type;
			if(file_exists($filename)) unlink($filename);
			move_uploaded_file($temp, $filename);
		}

		// resize alternative versions
	    $this->imageToFile($this->thumbnail($filename, 1000), $path.$id.'_'.$hash.'_1000.'.$type);
	    $this->imageToFile($this->thumbnailCrop($filename, 150), $path.$id.'_'.$hash.'_150.'.$type);

		// get imagelist
		$files = array();
		if($handle = opendir($path)) {
			while(false!==$entry=readdir($handle)) {
				$strLen = 1+strLen($id);
				if(substr($entry, 0, $strLen)=="$id"."_") {
					$files[] = $entry;
					$flag=1;
				}
			}
			closedir($handle);
		}
		if(!empty($files)) return serialize($files);
		else null;
	}


	private function thumbnail($inputFileName, $maxSize = 100) {
		$info = getimagesize($inputFileName);
 		$type = isset($info['type']) ? $info['type'] : $info[2];
 
		// Check support of file type
		if ( !(imagetypes() & $type) ) 
			return false;
 
		$width  = isset($info['width'])  ? $info['width']  : $info[0];
		$height = isset($info['height']) ? $info['height'] : $info[1];
 
		// Calculate aspect ratio
		$wRatio = $maxSize / $width;
		$hRatio = $maxSize / $height;
 
		// Using imagecreatefromstring will automatically detect the file type
		$sourceImage = imagecreatefromstring(file_get_contents($inputFileName));
 
		// Calculate a proportional width and height no larger than the max size.
		if( ($width <= $maxSize) && ($height <= $maxSize) ) {
			return $sourceImage;
		} elseif ( ($wRatio * $height) < $maxSize ) {
			$tHeight = ceil($wRatio * $height);
			$tWidth  = $maxSize;
		} else {
			$tWidth  = ceil($hRatio * $width);
			$tHeight = $maxSize;
		}
		$thumb = imagecreatetruecolor($tWidth, $tHeight);
 
		if ( $sourceImage === false ) {
			return false;
		}
 
		imagecopyresampled($thumb, $sourceImage, 0, 0, 0, 0, $tWidth, $tHeight, $width, $height);
		imagedestroy($sourceImage);
 
		return $thumb;
	}


	private function thumbnailCrop($inputFileName, $size = 100) {
		$info = getimagesize($inputFileName);
 		$type = isset($info['type']) ? $info['type'] : $info[2];
 
		// Check support of file type
		if ( !(imagetypes() & $type) ) {
			return false;
		}
 
		$width  = isset($info['width'])  ? $info['width']  : $info[0];
		$height = isset($info['height']) ? $info['height'] : $info[1];

 
		// Using imagecreatefromstring will automatically detect the file type
		$sourceImage = imagecreatefromstring(file_get_contents($inputFileName));
 


		// starting points
		if($width>=$height) {
			$src_w = $height;
			$src_h = $height;
			$src_x = ceil(($width-$height)/2);
			$src_y = 0;
		} else {
			$src_w = $width;
			$src_h = $width;
			$src_x = 0;
			$src_y = ceil(($height-$width)/2);
		}
		$thumb = imagecreatetruecolor($size, $size);


 
		if ( $sourceImage === false ) {
			return false;
		}

		//imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
		imagecopyresampled($thumb, $sourceImage, 0, 0, $src_x, $src_y, $size, $size, $src_w, $src_h);
		imagedestroy($sourceImage);
 
		return $thumb;
	}


	function imageToFile($im, $fileName, $quality = 80) {
		if( !$im || file_exists($fileName) ) {
		   return false;
		}
		$ext = strtolower(substr($fileName, strrpos($fileName, '.')));
		switch ( $ext ) {
			case '.gif':
				imagegif($im, $fileName);
				break;
			case '.jpg':
			case '.jpeg':
				imagejpeg($im, $fileName, $quality);
				break;
			case '.png':
				imagepng($im, $fileName);
				break;
			case '.bmp':
				imagewbmp($im, $fileName);
				break;
			default:
				return false;
		}
 
		return true;
	}

}

