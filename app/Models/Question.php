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
            'question-1' => 'Question 1',
            'question-2' => 'Question 2',
            'question-3' => 'Question 3',
            'reponse-1' => 'Réponse 1',
            'reponse-2' => 'Réponse 2',
            'reponse-3' => 'Réponse 3',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
