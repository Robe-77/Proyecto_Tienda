<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use mysqli;

class Producto
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByCodigo(string $codigo): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT Código_Producto, Nombre_Comercial, Precio_Público, Existencia
             FROM producto
             WHERE Código_Producto = ?'
        );
        $stmt->bind_param('s', $codigo);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result ?: null;
    }

    public function create(string $codigo, string $nombreComercial, float $precioPublico, int $existencia): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO producto (Código_Producto, Nombre_Comercial, Precio_Público, Existencia)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('ssdi', $codigo, $nombreComercial, $precioPublico, $existencia);

        return $stmt->execute();
    }

    public function update(string $codigo, string $nombreComercial, ?float $precioPublico): bool
    {
        $existing = $this->findByCodigo($codigo);
        if ($existing === null) {
            return false;
        }

        $nuevoNombre = $nombreComercial !== '' ? $nombreComercial : $existing['Nombre_Comercial'];
        $nuevoPrecio = $precioPublico !== null ? $precioPublico : (float) $existing['Precio_Público'];

        $stmt = $this->db->prepare(
            'UPDATE producto SET Nombre_Comercial = ?, Precio_Público = ? WHERE Código_Producto = ?'
        );
        $stmt->bind_param('sds', $nuevoNombre, $nuevoPrecio, $codigo);

        return $stmt->execute();
    }

    public function isInUse(string $codigo): bool
    {
        $checks = [
            'SELECT 1 FROM detalle_salida WHERE Código_Producto = ? LIMIT 1',
            'SELECT 1 FROM detalle_entrada WHERE Código_Producto = ? LIMIT 1',
        ];

        foreach ($checks as $sql) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $codigo);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                return true;
            }
        }

        return false;
    }

    public function delete(string $codigo): bool
    {
        $stmt = $this->db->prepare('DELETE FROM producto WHERE Código_Producto = ?');
        $stmt->bind_param('s', $codigo);
        return $stmt->execute();
    }
}
