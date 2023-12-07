<?php

namespace App\Actions\Contact;

use App\Models\Contact;
use App\Services\FileUploader;

class AddContactAction
{
    public function execute(
        string $name,
        string $surname,
        string $email,
        string $phone,
        string $category,
        string|null $image,
        int $favorite,
        int $user_id
    ) {
        if ($image) {
            if ($callback = FileUploader::uploadFromBase64($image, '/public/images/contacts')) {
                if (isset($callback['errors'])) {
                    return $callback;
                }
                Contact::create(
                    [
                     'name'     => $name,
                     'surname'  => $surname,
                     'email'    => $email,
                     'image'    => $callback,
                     'phone'    => $phone,
                     'category' => $category,
                     'favorite' => $favorite,
                     'user_id'  => $user_id,
                    ]
                );
                return true;
            } else {
                return [
                        "errors" => ["image" => "Impossible d'uploader l'image."],
                       ];
            }
        } else {
            Contact::create(
                [
                 'name'     => $name,
                 'surname'  => $surname,
                 'email'    => $email,
                 'phone'    => $phone,
                 'image'    => 'default.png',
                 'category' => $category,
                 'favorite' => $favorite,
                 'user_id'  => $user_id,
                ]
            );
            return true;
        }
    }
}
