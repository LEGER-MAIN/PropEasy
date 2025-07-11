<?php

require_once 'Utils.php';

/**
 * Clase para validación de formularios
 */
class Validator
{
    private $errors = [];
    private $data = [];
    
    public function __construct($data = [])
    {
        $this->data = $data;
    }
    
    /**
     * Valida un campo requerido
     */
    public function required($field, $message = null)
    {
        if (empty($this->data[$field])) {
            $this->errors[$field] = $message ?? "El campo {$field} es requerido";
        }
        return $this;
    }
    
    /**
     * Valida la longitud mínima
     */
    public function minLength($field, $length, $message = null)
    {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? "El campo {$field} debe tener al menos {$length} caracteres";
        }
        return $this;
    }
    
    /**
     * Valida la longitud máxima
     */
    public function maxLength($field, $length, $message = null)
    {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? "El campo {$field} no puede tener más de {$length} caracteres";
        }
        return $this;
    }
    
    /**
     * Valida formato de email
     */
    public function email($field, $message = null)
    {
        if (isset($this->data[$field]) && !Utils::validateEmail($this->data[$field])) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser un email válido";
        }
        return $this;
    }
    
    /**
     * Valida formato de teléfono
     */
    public function phone($field, $message = null)
    {
        if (isset($this->data[$field]) && !Utils::validatePhone($this->data[$field])) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser un teléfono válido";
        }
        return $this;
    }
    
    /**
     * Valida que sea un número
     */
    public function numeric($field, $message = null)
    {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser un número";
        }
        return $this;
    }
    
    /**
     * Valida que sea un número entero
     */
    public function integer($field, $message = null)
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser un número entero";
        }
        return $this;
    }
    
    /**
     * Valida valor mínimo
     */
    public function min($field, $min, $message = null)
    {
        if (isset($this->data[$field]) && $this->data[$field] < $min) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser mayor o igual a {$min}";
        }
        return $this;
    }
    
    /**
     * Valida valor máximo
     */
    public function max($field, $max, $message = null)
    {
        if (isset($this->data[$field]) && $this->data[$field] > $max) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser menor o igual a {$max}";
        }
        return $this;
    }
    
    /**
     * Valida que esté en una lista de valores
     */
    public function in($field, $values, $message = null)
    {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser uno de los valores permitidos";
        }
        return $this;
    }
    
    /**
     * Valida con expresión regular
     */
    public function regex($field, $pattern, $message = null)
    {
        if (isset($this->data[$field]) && !preg_match($pattern, $this->data[$field])) {
            $this->errors[$field] = $message ?? "El campo {$field} no tiene el formato correcto";
        }
        return $this;
    }
    
    /**
     * Valida que coincida con otro campo
     */
    public function matches($field, $matchField, $message = null)
    {
        if (isset($this->data[$field]) && isset($this->data[$matchField]) && 
            $this->data[$field] !== $this->data[$matchField]) {
            $this->errors[$field] = $message ?? "El campo {$field} debe coincidir con {$matchField}";
        }
        return $this;
    }
    
    /**
     * Valida formato de fecha
     */
    public function date($field, $format = 'Y-m-d', $message = null)
    {
        if (isset($this->data[$field])) {
            $date = DateTime::createFromFormat($format, $this->data[$field]);
            if (!$date || $date->format($format) !== $this->data[$field]) {
                $this->errors[$field] = $message ?? "El campo {$field} debe ser una fecha válida";
            }
        }
        return $this;
    }
    
    /**
     * Valida formato de URL
     */
    public function url($field, $message = null)
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser una URL válida";
        }
        return $this;
    }
    
    /**
     * Valida que sea booleano
     */
    public function boolean($field, $message = null)
    {
        if (isset($this->data[$field]) && !is_bool($this->data[$field]) && 
            !in_array($this->data[$field], [0, 1, '0', '1', 'true', 'false'])) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser verdadero o falso";
        }
        return $this;
    }
    
    /**
     * Valida archivo subido
     */
    public function file($field, $allowedTypes = [], $maxSize = 0, $message = null)
    {
        if (isset($_FILES[$field])) {
            $file = $_FILES[$field];
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->errors[$field] = $message ?? "Error al subir el archivo";
                return $this;
            }
            
            if (!empty($allowedTypes)) {
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($extension, $allowedTypes)) {
                    $this->errors[$field] = $message ?? "Tipo de archivo no permitido";
                    return $this;
                }
            }
            
            if ($maxSize > 0 && $file['size'] > $maxSize) {
                $this->errors[$field] = $message ?? "El archivo es demasiado grande";
                return $this;
            }
        }
        
        return $this;
    }
    
    /**
     * Valida imagen
     */
    public function image($field, $message = null)
    {
        if (isset($_FILES[$field])) {
            $file = $_FILES[$field];
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->errors[$field] = $message ?? "Error al subir la imagen";
                return $this;
            }
            
            $imageInfo = getimagesize($file['tmp_name']);
            if (!$imageInfo) {
                $this->errors[$field] = $message ?? "El archivo debe ser una imagen válida";
            }
        }
        
        return $this;
    }
    
    /**
     * Valida RUT chileno
     */
    public function rut($field, $message = null)
    {
        if (isset($this->data[$field]) && !Utils::validateRut($this->data[$field])) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser un RUT válido";
        }
        return $this;
    }
    
    /**
     * Valida contraseña segura
     */
    public function password($field, $message = null)
    {
        if (isset($this->data[$field])) {
            $password = $this->data[$field];
            
            if (strlen($password) < 8) {
                $this->errors[$field] = $message ?? "La contraseña debe tener al menos 8 caracteres";
                return $this;
            }
            
            if (!preg_match('/[A-Z]/', $password)) {
                $this->errors[$field] = $message ?? "La contraseña debe contener al menos una mayúscula";
                return $this;
            }
            
            if (!preg_match('/[a-z]/', $password)) {
                $this->errors[$field] = $message ?? "La contraseña debe contener al menos una minúscula";
                return $this;
            }
            
            if (!preg_match('/[0-9]/', $password)) {
                $this->errors[$field] = $message ?? "La contraseña debe contener al menos un número";
                return $this;
            }
        }
        
        return $this;
    }
    
    /**
     * Valida con función personalizada
     */
    public function custom($field, $callback, $message = null)
    {
        if (isset($this->data[$field])) {
            if (!call_user_func($callback, $this->data[$field])) {
                $this->errors[$field] = $message ?? "El campo {$field} no es válido";
            }
        }
        return $this;
    }
    
    /**
     * Agrega un error personalizado
     */
    public function addError($field, $message)
    {
        $this->errors[$field] = $message;
        return $this;
    }
    
    /**
     * Verifica si hay errores
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
    
    /**
     * Obtiene todos los errores
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Obtiene el primer error
     */
    public function getFirstError()
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Obtiene el error de un campo específico
     */
    public function getError($field)
    {
        return $this->errors[$field] ?? null;
    }
    
    /**
     * Limpia todos los errores
     */
    public function clearErrors()
    {
        $this->errors = [];
        return $this;
    }
    
    /**
     * Obtiene los datos validados
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Obtiene un valor específico
     */
    public function getValue($field, $default = null)
    {
        return $this->data[$field] ?? $default;
    }
    
    /**
     * Establece un valor
     */
    public function setValue($field, $value)
    {
        $this->data[$field] = $value;
        return $this;
    }
    
    /**
     * Valida todos los campos según reglas
     */
    public function validate($rules)
    {
        foreach ($rules as $field => $fieldRules) {
            if (is_string($fieldRules)) {
                $fieldRules = explode('|', $fieldRules);
            }
            
            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return !$this->hasErrors();
    }
    
    /**
     * Aplica una regla específica
     */
    private function applyRule($field, $rule)
    {
        if (strpos($rule, ':') !== false) {
            list($ruleName, $ruleValue) = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
            $ruleValue = null;
        }
        
        switch ($ruleName) {
            case 'required':
                $this->required($field);
                break;
            case 'min':
                $this->min($field, (int)$ruleValue);
                break;
            case 'max':
                $this->max($field, (int)$ruleValue);
                break;
            case 'min_length':
                $this->minLength($field, (int)$ruleValue);
                break;
            case 'max_length':
                $this->maxLength($field, (int)$ruleValue);
                break;
            case 'email':
                $this->email($field);
                break;
            case 'phone':
                $this->phone($field);
                break;
            case 'numeric':
                $this->numeric($field);
                break;
            case 'integer':
                $this->integer($field);
                break;
            case 'in':
                $values = explode(',', $ruleValue);
                $this->in($field, $values);
                break;
            case 'regex':
                $this->regex($field, $ruleValue);
                break;
            case 'matches':
                $this->matches($field, $ruleValue);
                break;
            case 'date':
                $this->date($field, $ruleValue ?? 'Y-m-d');
                break;
            case 'url':
                $this->url($field);
                break;
            case 'boolean':
                $this->boolean($field);
                break;
            case 'rut':
                $this->rut($field);
                break;
            case 'password':
                $this->password($field);
                break;
        }
    }
    
    /**
     * Método estático para validación rápida
     */
    public static function make($data, $rules)
    {
        $validator = new self($data);
        $validator->validate($rules);
        return $validator;
    }
    
    /**
     * Sanitiza los datos
     */
    public function sanitize()
    {
        foreach ($this->data as $key => $value) {
            if (is_string($value)) {
                $this->data[$key] = Utils::cleanString($value);
            }
        }
        return $this;
    }
    
    /**
     * Convierte los errores a JSON
     */
    public function errorsToJson()
    {
        return Utils::jsonEncode($this->errors);
    }
    
    /**
     * Convierte los errores a string
     */
    public function errorsToString($separator = ', ')
    {
        return implode($separator, $this->errors);
    }
}
?> 