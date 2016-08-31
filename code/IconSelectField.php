<?php
class IconSelectField extends OptionsetField {
	
	static $sourceFolder;
	
	/**
	 * Construct the field
	 *
	 * @param string $name
	 * @param null|string $title
	 * @param string $sourceFolder
	 **/
	public function __construct($name, $title = null, $sourceFolder = '/site/icons/' ){
	
		parent::__construct($name, $title, null);
		
		$sourcePath = BASE_PATH.$sourceFolder;		
		
		// image extensions
		$extensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg');

		// init result
		$icons = array();

		// directory to scan
		$directory = new DirectoryIterator($sourcePath);

		// iterate
		foreach ($directory as $fileinfo){
			// must be a file
			if ($fileinfo->isFile()) {
				// file extension
				$extension = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));
				// check if extension match
				if (in_array($extension, $extensions)){					
					$icons[$sourceFolder.$fileinfo->getFilename()] = $fileinfo->getFilename();
				}
			}
		}
		
		$this->source = $icons;
		
		Requirements::css( DEVTOOLS_DIR .'/css/IconSelectField.css' );
		Requirements::javascript( DEVTOOLS_DIR .'/js/IconSelectField.js' );
	}
	
	public function Field($properties = array()) {
		$source = $this->getSource();
		$odd = 0;

		if($source) {
			foreach($source as $value => $title) {
				$itemID = $this->ID() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $value);
				$options[] = new ArrayData(array(
					'ID' => $itemID,
					'Name' => $this->name,
					'Value' => $value,
					'Title' => $title,
					'isChecked' => $value == $this->value
				));
			}
		}

		$properties = array_merge($properties, array(
			'Options' => new ArrayList($options)
		));

		return $this->customise($properties)->renderWith('IconSelectField');
		/*
		return $this->customise($properties)->renderWith(
			$this->getTemplates()
		);*/
	}
}




