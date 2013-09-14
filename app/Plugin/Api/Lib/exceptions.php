<?php

	namespace Saito\Api;


	class GenericApiError extends \BadRequestException {

		public function __construct($message = '') {
			if (empty($message)) {
				$message = 'Api Error. Check URL, request type and headers.';
			}
			parent::__construct($message);
		}

	}

	class ApiDisabledException extends GenericApiError {
		public function __construct($message = '') {
			$message = 'API is disabled.';
			parent::__construct($message);
		}
	}

	class UnknownRouteException extends GenericApiError {

		public function __construct($message = '') {
			if (empty($message)) {
				$message = 'Unknown REST route. Check URL and request type (GET, POST, …).';
			}
			parent::__construct($message);
		}

	}

    class ApiValidationError extends GenericApiError {
        public function __construct($field, $rule) {
            $lookup =  $field . ' ' . $rule;
            \Configure::write('Config.language', 'eng');
            $message = __d('api', $lookup);
            $no_explanation = $lookup === $message;
            if ($no_explanation) {
               $message = "Internal validation error. Field: `$field` Rule: `$rule`.";
            }
            parent::__construct($message);
        }
    }