<?php 

namespace App\Models;

use App\Core\Model;

class Contact extends Model
{

	public static function getTable(): string
    {
        return 'contacts';
    }

    public static function getAttributes(): array
    {
        return [
            'name', 
            'surname', 
            'email', 
            'phone', 
            'image',
            'category', 
            'favorite', 
            'user_id'
        ];
    }

    public static function getLabels(): array
    {
        return [
            'name' => 'Nom',
            'surname' => 'Prénom',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'image' => 'Photo',
            'category' => 'Catégorie',
            'favorite' => 'Favori'
        ];
    }

}