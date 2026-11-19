<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use mysqli;
use RuntimeException;
use Throwable;

class Movimiento
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Register a sale atomically with stock validation.
     *
     * @param array<int, array{codigo: string, cantidad: int}> $items
     * @return array{id_salida: int, total: float}
     */
    public function registrarVenta(int $idEmpleado, array $items): array
    {
        if (empty($items)) {
            throw new RuntimeException('Debes agregar al menos un producto a la venta.');
        }

        $this->db->begin_transaction();

        try {
            $stmtSalida = $this->db->prepare('INSERT INTO salida (Fecha, Id_Empleado, Total) VALUES (NOW(), ?, 0)');
            $stmtSalida->bind_param('i', $idEmpleado);
            $stmtSalida->execute();
            $idSalida = (int) $this->db->insert_id;

            $total = 0.0;

            $stmtProducto = $this->db->prepare(
                'SELECT Precio_Público, Existencia FROM producto WHERE Código_Producto = ? FOR UPDATE'
            );
            $stmtDetalle = $this->db->prepare(
                'INSERT INTO detalle_salida (Id_Salida, Código_Producto, Cantidad) VALUES (?, ?, ?)'
            );
            $stmtStock = $this->db->prepare(
                'UPDATE producto SET Existencia = Existencia - ? WHERE Código_Producto = ?'
            );

            foreach ($items as $item) {
                $codigo = $item['codigo'];
                $cantidad = $item['cantidad'];

                if ($cantidad <= 0) {
                    throw new RuntimeException("Cantidad inválida para el producto {$codigo}.");
                }

                $stmtProducto->bind_param('s', $codigo);
                $stmtProducto->execute();
                $prod = $stmtProducto->get_result()->fetch_assoc();

                if ($prod === null) {
                    throw new RuntimeException("El producto con código {$codigo} no existe.");
                }

                if ((int) $prod['Existencia'] < $cantidad) {
                    throw new RuntimeException(
                        "Stock insuficiente para el producto {$codigo} (disponible: {$prod['Existencia']})."
                    );
                }

                $subtotal = $cantidad * (float) $prod['Precio_Público'];
                $total += $subtotal;

                $stmtDetalle->bind_param('isi', $idSalida, $codigo, $cantidad);
                $stmtDetalle->execute();

                $stmtStock->bind_param('is', $cantidad, $codigo);
                $stmtStock->execute();
            }

            $stmtUpdate = $this->db->prepare('UPDATE salida SET Total = ? WHERE Id_Salida = ?');
            $stmtUpdate->bind_param('di', $total, $idSalida);
            $stmtUpdate->execute();

            $this->db->commit();

            return [
                'id_salida' => $idSalida,
                'total' => $total,
            ];
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Register a product entry (Compra or Devolución) atomically.
     */
    public function registrarEntrada(int $idEmpleado, ?string $idProveedor, string $codigoProducto, int $cantidad, string $tipo): void
    {
        if ($cantidad <= 0) {
            throw new RuntimeException('La cantidad debe ser mayor a cero.');
        }

        $this->db->begin_transaction();

        try {
            $stmtCheck = $this->db->prepare('SELECT 1 FROM producto WHERE Código_Producto = ? FOR UPDATE');
            $stmtCheck->bind_param('s', $codigoProducto);
            $stmtCheck->execute();
            if ($stmtCheck->get_result()->num_rows === 0) {
                throw new RuntimeException("El producto con código {$codigoProducto} no existe.");
            }

            $stmtEntrada = $this->db->prepare(
                'INSERT INTO entrada_producto (Fecha, Id_Empleado, Id_Proveedor) VALUES (NOW(), ?, ?)'
            );
            $stmtEntrada->bind_param('is', $idEmpleado, $idProveedor);
            $stmtEntrada->execute();
            $idEntrada = (int) $this->db->insert_id;

            $stmtDetalle = $this->db->prepare(
                'INSERT INTO detalle_entrada (Id_Entrada, Código_Producto, Tipo, Cantidad) VALUES (?, ?, ?, ?)'
            );
            $stmtDetalle->bind_param('issi', $idEntrada, $codigoProducto, $tipo, $cantidad);
            $stmtDetalle->execute();

            $stmtStock = $this->db->prepare(
                'UPDATE producto SET Existencia = Existencia + ? WHERE Código_Producto = ?'
            );
            $stmtStock->bind_param('is', $cantidad, $codigoProducto);
            $stmtStock->execute();

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEntradas(string $fechaFiltro = '', string $codigoFiltro = ''): array
    {
        $conditions = [];
        $types = '';
        $params = [];

        if ($fechaFiltro !== '') {
            $conditions[] = 'DATE(ep.Fecha) = ?';
            $types .= 's';
            $params[] = $fechaFiltro;
        }
        if ($codigoFiltro !== '') {
            $conditions[] = 'de.Código_Producto = ?';
            $types .= 's';
            $params[] = $codigoFiltro;
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = "
            SELECT ep.Fecha, ep.Id_Empleado, de.Código_Producto, de.Tipo, de.Cantidad
            FROM entrada_producto ep
            JOIN detalle_entrada de ON ep.Id_Entrada = de.Id_Entrada
            {$where}
            ORDER BY ep.Fecha DESC
        ";

        $stmt = $this->db->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getSalidas(string $fechaFiltro = '', string $codigoFiltro = ''): array
    {
        $conditions = [];
        $types = '';
        $params = [];

        if ($fechaFiltro !== '') {
            $conditions[] = 'DATE(s.Fecha) = ?';
            $types .= 's';
            $params[] = $fechaFiltro;
        }
        if ($codigoFiltro !== '') {
            $conditions[] = 'ds.Código_Producto = ?';
            $types .= 's';
            $params[] = $codigoFiltro;
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = "
            SELECT s.Fecha, s.Id_Empleado, ds.Código_Producto, ds.Cantidad
            FROM salida s
            JOIN detalle_salida ds ON s.Id_Salida = ds.Id_Salida
            {$where}
            ORDER BY s.Fecha DESC
        ";

        $stmt = $this->db->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
