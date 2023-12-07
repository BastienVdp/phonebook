<?php

namespace App\Actions\Contact;

use App\Models\Contact;
use App\Services\FileUploader;

class UpdateContactAction
{
    public function execute(
        string $name,
        string $surname,
        string $email,
        string $phone,
        string $category,
        bool $favorite,
        string|null $image,
        int $user_id,
        int $contactId
    ) {
        $attributes = [
                       'name'     => $name,
                       'surname'  => $surname,
                       'email'    => $email,
                       'phone'    => $phone,
                       'category' => $category,
                       'favorite' => $favorite ? 1 : 0,
                       'user_id'  => $user_id,
                      ];

        if ($image) {
            if ($callback = FileUploader::uploadFromBase64($image, '/public/images/contacts')) {
                if (isset($callback['errors'])) {
                    return $callback;
                }
                $attributes['image'] = $callback;
            }
        }

        Contact::update(['id' => $contactId, 'user_id' => $user_id], $attributes);

        return true;
    }
}
