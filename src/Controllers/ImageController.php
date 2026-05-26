<?php

namespace App\Controllers;

use App\Database\Connection;

class ImageController
{
    public static function serve(array $vars): void
    {
        $id = (int) ($vars['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            exit('ID inválido.');
        }

        $db   = Connection::get();
        $stmt = $db->prepare('SELECT image_data, mime_type FROM image WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            http_response_code(404);
            exit('Imagem não encontrada.');
        }

        // Cabeçalhos de cache para não reprocessar a cada requisição
        header('Content-Type: ' . $row['mime_type']);
        header('Cache-Control: private, max-age=86400');
        echo $row['image_data'];
        exit();
    }
}