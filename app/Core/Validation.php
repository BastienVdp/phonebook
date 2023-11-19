<?php

namespace App\Core;
class Validation
{
    public static array $labels;
    
    public static function validate(Request $request, array $rules, string $model): array
    {
        $errors = [];

        foreach ($rules as $key => $rule) {
            $value = $request->body[$key] ?? null;

            $ruleParts = explode('|', $rule);

            foreach ($ruleParts as $rulePart) {
                $ruleDetails = explode(':', $rulePart);

                $ruleName = $ruleDetails[0];
                $ruleValue = $ruleDetails[1] ?? null;

                $methodName = 'validate' . ucfirst($ruleName);

                if (method_exists(self::class, $methodName)) {
                    self::$labels = $model::getLabels();
                    $error = self::$methodName($value, $ruleValue, $key, $request, $model);
                    if ($error) {
                        $errors[$key] = $error;
                        break;
                    }
                }
            }
        }

        return $errors;
    }

    private static function validateRequired($value, $ruleValue, $key, $request)
    {
        if ($value === null || $value === '') {
            $key = str_replace('_confirmation', '', $key);
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' est requis.';
        }

        return null;
    }

    private static function validateMin($value, $ruleValue, $key, $request)
    {
        if (strlen($value) < $ruleValue) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' doit avoir au minimum <b>' . $ruleValue . '</b> caractères.';
        }

        return null;
    }

    private static function validateMax($value, $ruleValue, $key, $request)
    {
        if (strlen($value) > $ruleValue) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' doit avoir au maximum <b>' . $ruleValue . '</b> caractères.';
        }

        return null;
    }

    private static function validateEmail($value, $ruleValue, $key, $request)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' doit être une adresse e-mail valide.';
        }

        return null;
    }

    private static function validateUnique($value, $ruleValue, $key, $request, $model)
    {
        $result = $model::find([$key => $value]);

        if ($result) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' doit être unique.';
        }

        return null;
    }

    private static function validateConfirmed($value, $ruleValue, $key, $request)
    {
        $confirmationKey = str_replace('_confirmation', '', $key);
        if ($value !== $request->body[$confirmationKey]) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' de confirmation ne correspond pas.';
            }

        return null;
    }

    private static function validateNumeric($value, $ruleValue, $key, $request)
    {
        if (!is_numeric($value)) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' doit être un nombre.';
        }

        return null;
    }

    private static function validateImage($value, $ruleValue, $key, $request)
    {
        if (!is_array($value) || !isset($value['tmp_name'])) {
            // Si le champ est null, aucune validation nécessaire
            if ($value === null) {
                return null;
            }
            
            return "Le champ " . strtolower(self::$labels[$key]) . " doit être un fichier.";
        }

        $allowedExtensions = explode(',', $ruleValue);
        $extension = pathinfo($value['name'], PATHINFO_EXTENSION);

        if (!in_array($extension, $allowedExtensions)) {
            return "Le champ " . strtolower(self::$labels[$key]) . " doit être un fichier de type : " . $ruleValue;
        }

        return null;
    }
}
