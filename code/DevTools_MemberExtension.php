<?php

/**
 * Injects our betterbutton into the Member dataobject
 **/
class DevTools_MemberExtension extends DataExtension {

    private static $better_buttons_actions = array (
        'send_instructions',
        'emulate_user'
    );
	
    public function updateBetterButtonsActions($fields) {

        // Emulate user button
        // We only show if the user has permission, and the config is enabled
        if (Permission::check('ADMIN') && Config::inst()->get('DevTools','user_emulation')){
	        $fields->push(BetterButtonCustomAction::create('emulate_user', 'Emulate this user'));
	    }

        $fields->push(BetterButtonCustomAction::create('send_instructions', 'Send login instructions'));
        
        return $fields;
    }

    /**
     * Login as this user
     **/
    public function emulate_user(){    

		if (!Config::inst()->get('DevTools','user_emulation')){
			return 'User emulation not enabled';
		}

		// not enabled, or not allowed >> get out
		if (Permission::check('ADMIN')){
    		$this->owner->login();
        	return "Switched to user ".$this->owner->Email;
        }

        return "Unknown error";
    }

    /**
     * Email this user's credentials to their email address
     **/
    public function send_instructions(){

    	if (!$this->owner->canEdit()){
    		return "Permission denied";
    	}

		$subject = 'Website login instructions';
		$to = '"'.$this->owner->FirstName.' '.$this->owner->Surname.'" <'.$this->owner->Email.'>';
		$email = Email::create("noreply@plasticstudio.co", $to, $subject, null);

		// Construct our template data
		$data = array();

		$data['AbsoluteDomain'] = SS_PRIMARY_DOMAIN."/";
		$data['TimeSent'] = date('Y-m-d H:i:s');
		$data['Title'] = 'Hi '.$this->owner->FirstName.',';
		$data['Intro'] = 'Please find below login instructions to the website. If you have any trouble logging in, please contact your website administrator or <a href="mailto:support@plasticstudio.co.nz">support@plasticstudio.co.nz</a>.';
		$data['URL'] = Director::absoluteBaseURL();

		$data['Data'] = ArrayList::create(array(
			ArrayData::create(array(
				'Title' => 'URL',
				'Link' => $data['AbsoluteDomain'].'admin',
				'Value' => $data['AbsoluteDomain'].'admin'
			)),
			ArrayData::create(array(
				'Title' => 'Email',
				'Value' => $this->owner->Email
			)),
			ArrayData::create(array(
				'Title' => 'Password',
				'Value' => "&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull; <a href='".$data['AbsoluteDomain']."Security/lostpassword'>Reset password</a>"
			))
		));

		// populate template
		$email->setTemplate('Email');
		$email->populateTemplate( ArrayData::create($data) );
		$email->send();

        return "Sent login indstructions to ".$this->owner->Email;
    }
}