<?php 

namespace App\Models;

use App\Core\Model;
use App\Trait\Relationship;

class Question extends Model
{
    use Relationship;

    public static function getTable(): string
    {
        return 'questions_users';
    }

    public static function getAttributes(): array
    {
        return [
            'question', 
            'reponse',
            'user_id'
        ];
    }

    public static function getLabels(): array
    {
        return [
            'question' => 'Question',
            'reponse' => 'Réponse',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
