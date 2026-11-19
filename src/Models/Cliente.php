<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use mysqli;

class Cliente
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByRfc(string $rfc): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM cliente WHERE RFC = ?');
        $stmt->bind_param('s', $rfc);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result ?: null;
    }

    public function existsByRfc(string $rfc): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM cliente WHERE RFC = ? LIMIT 1');
        $stmt->bind_param('s', $rfc);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO cliente
                (Nombre, RFC, Apellido, Calle, Número, Colonia, Ciudad, Estado, Código_Postal, Teléfono, Correo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'sssssssssss',
            $data['nombre'],
            $data['rfc'],
            $data['apellido'],
            $data['calle'],
            $data['numero'],
            $data['colonia'],
            $data['ciudad'],
            $data['estado'],
            $data['codigo_postal'],
            $data['telefono'],
            $data['correo']
        );

        return $stmt->execute();
    }
}
