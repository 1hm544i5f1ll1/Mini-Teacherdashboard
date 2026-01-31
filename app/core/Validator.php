<?php
namespace App\Core;

class Validator {
    private $errors = [];

    public function validate($data, $rules) {
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $ruleArray = explode('|', $fieldRules);

            foreach ($ruleArray as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $this->errors[$field] = ucfirst($field) . " is required.";
                } elseif (strpos($rule, 'enum:') === 0) {
                    $options = explode(',', substr($rule, 5));
                    if (!in_array($value, $options)) {
                        $this->errors[$field] = "Invalid value for " . $field;
                    }
                } elseif (strpos($rule, 'min:') === 0) {
                    $min = (int)substr($rule, 4);
                    if (strlen($value) < $min) {
                        $this->errors[$field] = ucfirst($field) . " must be at least $min characters.";
                    }
                }
                // Add more rules as needed
            }
        }
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}
