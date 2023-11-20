<?php

namespace App\Core;
class Validation
{
    public static array $labels;
    
    public static function validate(Request $request, array $rules, string $model): array
    {
        $errors = [];

        foreach ($rules as $key => $rule) {
            $value = $request->body[$key] ?? null; // On récupère la valeur du champ

            $ruleParts = explode('|', $rule); // On récupère les règles de validation sous forme de tableau

            foreach ($ruleParts as $rulePart) { // On parcourt les règles de validation
                $ruleDetails = explode(':', $rulePart); // On récupère le nom de la règle et sa valeur éventuelle sous forme de tableau

                $ruleName = $ruleDetails[0]; // On récupère le nom de la règle
                $ruleValue = $ruleDetails[1] ?? null; // On récupère la valeur de la règle

                $methodName = 'validate' . ucfirst($ruleName); // On crée le nom de la méthode de validation avec cette règle

                if (method_exists(self::class, $methodName)) { // On vérifie si la méthode existe
                    self::$labels = $model::getLabels(); // On récupère les labels du modèle
                    $error = self::$methodName($value, $ruleValue, $key, $request, $model); // On exécute la méthode de validation
                    if ($error) { // Si la méthode de validation retourne une erreur
                        $errors[$key] = $error; // On ajoute l'erreur au tableau des erreurs
                        break; // On arrête la boucle
                    }
                }
            }
        }

        return $errors;
    }

    private static function validateRequired($value, $ruleValue, $key, $request, $model)
    {
        if ($value === null || $value === '') {
            $key = str_replace('_confirmation', '', $key);
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' est requis.';
        }

        return null;
    }

    private static function validateMin($value, $ruleValue, $key, $request, $model)
    {
        if (strlen($value) < $ruleValue) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' doit avoir au minimum <b>' . $ruleValue . '</b> caractères.';
        }

        return null;
    }

    private static function validateMax($value, $ruleValue, $key, $request, $model)
    {
        if (strlen($value) > $ruleValue) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' doit avoir au maximum <b>' . $ruleValue . '</b> caractères.';
        }

        return null;
    }

    private static function validateEmail($value, $ruleValue, $key, $request, $model)
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

    private static function validateConfirmed($value, $ruleValue, $key, $request, $model)
    {
        $confirmationKey = str_replace('_confirmation', '', $key);
        if ($value !== $request->body[$confirmationKey]) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' ne correspond pas.';
            }

        return null;
    }

    private static function validateNumeric($value, $ruleValue, $key, $request, $model)
    {
        if (!is_numeric($value)) {
            return 'Le champ ' . strtolower(self::$labels[$key]) . ' doit être un nombre.';
        }

        return null;
    }

    private static function validateImage($value, $ruleValue, $key, $request, $model)
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
