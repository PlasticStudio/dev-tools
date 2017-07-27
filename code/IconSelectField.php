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
	public function __construct($name, $title = null, $sourceFolder = '/site/icons/'){	
		parent::__construct($name, $title, null);
		
		$icons = array();
		$sourcePath = BASE_PATH.$sourceFolder;
		$extensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg');

		// Scan each directory for files
		$directory = new DirectoryIterator($sourcePath);
		foreach ($directory as $fileinfo){
			if ($fileinfo->isFile()){

				$extension = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));

				// Only add to our available icons if it's an extension we're after
				if (in_array($extension, $extensions)){					
					$icons[$sourceFolder.$fileinfo->getFilename()] = $fileinfo->getFilename();
				}
			}
		}
		
		$this->source = $icons;		
		Requirements::css(DEVTOOLS_DIR .'/css/IconSelectField.css');
		Requirements::javascript(DEVTOOLS_DIR .'/js/IconSelectField.js');
	}
	

	/**
	 * Build the field
	 *
	 * @return HTML
	 **/
	public function Field($properties = array()) {
		$source = $this->getSource();
		$odd = 0;
		$options = array();

		// Add a clear option
		$options[] = new ArrayData(array(
			'ID' => 0,
			'Name' => $this->name,
			'Value' => '',
			'Title' => 'test',
			'isChecked' => (!$this->value || $this->value == '')
		));

		if ($source){
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
	}
}




