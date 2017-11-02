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
		$url = $this->URL();
		
		// We are an SVG, so return the SVG data
		if (substr($url, strlen($url) - 4) === '.svg'){
			return $this->SVG();
		} else {
			return $this->IMG();
		}
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
	 * Construct IMG tag
	 *
	 * @return string
	 **/
	public function IMG(){
		$url = $this->URL();	
		return '<img class="icon" src="'.$url.'" />';
	}
	
	
	/** 
	 * Construct SVG data
	 *
	 * @return string
	 **/
	public function SVG(){
		$url = $this->URL();
		
		// figure out the full system location for the file
		$filePath = BASE_PATH.$url;
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