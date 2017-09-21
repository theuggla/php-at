<?php

namespace view;

	class LogoutView implements IUseCaseView {
    	private static $logout = 'LoginView::Logout';
		private static $messageId = 'LoginView::Message';

		public function renderHeading() {
			return '<h2>Logged in</h2>';
		}

		public function renderNavigation() {
			return '';
		}

    	public function renderBodyWithMessage($message = '')
    	{
        	$response = $this->generateLogoutButtonHTML($message);
        	return $response;
    	}

    	private function generateLogoutButtonHTML($message)
    	{
        	return '
				<form  method="post" >
					<p id="' . self::$messageId . '">' . $message .'</p>
					<input type="submit" name="' . self::$logout . '" value="logout"/>
				</form>
			';
    	}
	}
?>