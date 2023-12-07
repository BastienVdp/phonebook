<?php

namespace App\Services;

use App\Core\Application;

class FileUploader
{
    public static function uploadFromBase64(string $base64Data, string $path): array|string
    {
        // Retire l'en-tête "data:image/png;base64," pour obtenir seulement les données base64
        $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);

        $decodedData = base64_decode($base64Data);

        if ($decodedData === false) {
            return [
                    'errors' => ["image" => 'Échec du décodage de l\'image base64.'],
                   ];
        }

        // Utiliser finfo_buffer pour déterminer le type MIME de l'image
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $decodedData);
        finfo_close($finfo);

        // Traduire le type MIME en extension de fichier
        $allowedExtensions = [
                              'image/jpeg' => 'jpg',
                              'image/png'  => 'png',
                              'image/gif'  => 'gif',
                             ];

        $extension = $allowedExtensions[$mimeType] ?? null;

        if ($extension === null) {
            return [
                    'errors' => ["image" => 'Type d\'image non pris en charge.'],
                   ];
        }

        // Générer un nom de fichier unique avec l'extension appropriée
        $uniqueFilename = uniqid() . '.' . $extension;

        $destination = Application::$root_dir . $path . '/' . $uniqueFilename;

        if (file_put_contents($destination, $decodedData) !== false) {
            return $uniqueFilename;
        } else {
            return [
                    'errors' => ["image" => 'Échec de l\'enregistrement de l\'image.'],
                   ];
        }
    }
    /**
     * The function takes a file and a path as parameters, checks for any errors during file upload,
     * generates a unique filename, moves the file to the specified destination, and returns the unique
     * filename if successful.
     *
     * @param file The file parameter is an array that contains information about the uploaded file. It
     * typically includes the following keys:
     * @param path The "path" parameter is the directory where you want to save the uploaded file.
     *
     * @return either the unique filename if the file was successfully uploaded, or false if there was an
     * error during the upload process.
     */
    public static function upload($file, $path): string|bool
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Une erreur s'est produite lors du téléchargement du fichier.";
        }

        $uniqueFilename = uniqid() . '_' . $file['name'];

        $destination = Application::$root_dir . $path . '/' . $uniqueFilename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $uniqueFilename;
        } else {
            return false;
        }
    }

    /**
     * The function deletes a file from a specified path in a PHP application.
     *
     * @param file The file parameter is the name of the file that you want to delete.
     * @param path The path parameter is the directory path where the file is located.
     *
     * @return true if the file exists and is successfully deleted.
     */
    public static function delete($file, $path): bool
    {
        $fullPath = Application::$root_dir . $path . '/' . $file;

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return true;
        }
        return false;
    }
}
