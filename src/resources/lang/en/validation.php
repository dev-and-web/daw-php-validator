<?php

/**
 * Validation - English
 */

return [

    /**
     * Validation success message
     */
    'success_message' => 'The form has been validated.',
    
    /**
     * Validation error messages
     */
    'alpha' => 'The "{field}" field must contain only alphabetical characters without accents and without special characters.',
    'alpha_numeric' => 'The "{field}" field must contain only alphanumeric characters.',
    'confirm' => 'Field "{field}" and "Confirmation of {field}" should be identical.',
    'between' => 'The "{field}" field must have a value between {value_0} and {value_1}.',
    'empty' => 'The "{field}" field must remain empty.',
    'format_date' => 'The "{field}" field must have the value the format of a date (jj/mm/aaaa).',
    'format_date_time' => 'The "{field}" field must have the value the format of a date/hour (example: 05/09/2016 18:56).',
    'format_email' => 'The "{field}" field must have the value the format of a email address.',
    'format_ip' => 'The "{field}" field must have the value the format of a IP address.',
    'format_name_file' => 'A file name can not contain space, special character, or the following characters: \ / : * ? " < > |',
    'format_postal_code' => 'The "{field}" field must have value as the format of a postal code.',
    'format_slug' => 'The "{field}" field is not the right format.',
    'format_tel' => 'The "{field}" field must have the value the format of a Phone number.',
    'format_url' => 'The "{field}" field must have the format of a URL.',
    'integer' => 'The "{field}" field must be an integer.',
    'in_array' => 'The selected option does not exist in the "{field}" field.',
    'max' => 'The "{field}" field must not exceed {value} characteres.',
    'min' => 'The "{field}" field must not be less than {value} characteres.',
    'no_regex' => 'The "{field}" field must not have value in a format with the regex "{value}"',
    'not_in_array' => 'The "{field}" field is invalid',
    'regex' => 'The "{field}" field must have value as a format with the regex "{value}"',
    'required' => 'The "{field}" field is required.',

    /**
     * Custom validation attributes
     */
    'labels' => [
        "first_name" => "First Name",
        "last_name" => "Last Name",
    ],
    
];
