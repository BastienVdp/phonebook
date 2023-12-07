<?php

namespace App\Models;

use App\Core\Model;
use App\Trait\Relationship;

class User extends Model
{
    use Relationship;

    public static function getTable(): string
    {
        return 'users';
    }

    public static function getAttributes(): array
    {
        return [
                'username',
                'name',
                'surname',
                'email',
                'password',
               ];
    }

    public static function getLabels(): array
    {
        return [
                'username'                 => 'Nom d\'utilisateur',
                'name'                     => 'Nom',
                'surname'                  => 'Prénom',
                'email'                    => 'Email',
                'password'                 => 'Mot de passe',
                'password_confirmation'    => 'Confirmation du mot de passe',
                'newPassword'              => 'Nouveau mot de passe',
                'newPassword_confirmation' => 'Nouveau mot de passe',
               ];
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
