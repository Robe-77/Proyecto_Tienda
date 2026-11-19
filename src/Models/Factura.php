<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use mysqli;

class Factura
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(string $rfc, int $idSalida): bool
    {
        $stmt = $this->db->prepare('INSERT INTO factura (Fecha, RFC, Id_Salida) VALUES (NOW(), ?, ?)');
        $stmt->bind_param('si', $rfc, $idSalida);

        return $stmt->execute();
    }

    public function findBySalida(int $idSalida): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM factura WHERE Id_Salida = ?');
        $stmt->bind_param('i', $idSalida);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result ?: null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getSaleItems(int $idSalida): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.Nombre_Comercial, ds.Cantidad, p.Precio_Público,
                    (ds.Cantidad * p.Precio_Público) AS Subtotal
             FROM detalle_salida ds
             JOIN producto p ON ds.Código_Producto = p.Código_Producto
             WHERE ds.Id_Salida = ?'
        );
        $stmt->bind_param('i', $idSalida);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSaleTotal(int $idSalida): float
    {
        $stmt = $this->db->prepare('SELECT Total FROM salida WHERE Id_Salida = ?');
        $stmt->bind_param('i', $idSalida);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        return (float) ($row['Total'] ?? 0);
    }
}
