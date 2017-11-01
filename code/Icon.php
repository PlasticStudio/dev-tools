<?php
class Icon extends DBField {

    function requireField() {
        DB::requireField($this->tableName, $this->name, 'Varchar(1024)');
    }
	
	
	/** 
	 * Detect if the specified file exists
	 *
	 * @return boolean
	 **/
	public function fileExists( $url ){
		$filePath = BASE_PATH.$url;
		return file_exists($filePath);
	}
	

	/**
	 * Return an XHTML img tag for this Image.
	 *
	 * @return string
	 */
	public function forTemplate() {
		return $this->getTag();
	}
	

	/**
	 * Return an XHTML img tag for this Image,
	 * or NULL if the image file doesn't exist on the filesystem.
	 *
	 * @return string
	 */
	public function getTag() {
	
		$url = $this->getValue();
		
		if (!$this->fileExists($url)){
			return false;
		}

		return '<img class="icon" src="'.$url.'" />';
	}
	
	
	/** 
	 * Get just the URL for this icon
	 *
	 * @return string
	 **/
	public function URL(){
		return $this->getValue();
	}
	
	
	/** 
	 * Load the SVG data of the stored file
	 *
	 * @return string
	 **/
	public function SVG($url){
		
		// figure out the full system location for the file
		$filePath = BASE_PATH.$url;
		
		// not an SVG file
		if (substr($filePath, strlen($filePath) - 4) !== '.svg'){
			return false;
		}

		if (!file_exists($filePath)){
			return false;
		}
		
		$svg = file_get_contents($filePath);		
		return '<span class="icon svg">'.$svg.'</span>';
	}

	/**
	 * (non-PHPdoc)
	 * @see DBField::scaffoldFormField()
	 */
	public function scaffoldFormField($title = null, $params = null) {
		return new IconSelectField($this->name, $title);
	}
}