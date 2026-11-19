<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use mysqli;

class Empleado
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT Id_Empleado, Contraseña, Nombre, Apellido, Fecha_Ingreso, Fecha_Nacimiento,
                    Calle, Número, Colonia, Ciudad, Estado, Código_Postal, Teléfono, Rol
             FROM empleado
             WHERE Id_Empleado = ?'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $hash = password_hash($data['contraseña'], PASSWORD_DEFAULT);
        $numero = (int) $data['numero'];
        $codigoPostal = (int) $data['codigo_postal'];

        $stmt = $this->db->prepare(
            'INSERT INTO empleado
                (Contraseña, Nombre, Apellido, Fecha_Ingreso, Fecha_Nacimiento, Calle, Número, Colonia, Ciudad, Estado, Código_Postal, Teléfono, Rol)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'ssssssisssiss',
            $hash,
            $data['nombre'],
            $data['apellido'],
            $data['fecha_ingreso'],
            $data['fecha_nacimiento'],
            $data['calle'],
            $numero,
            $data['colonia'],
            $data['ciudad'],
            $data['estado'],
            $codigoPostal,
            $data['telefono'],
            $data['rol']
        );

        return $stmt->execute();
    }

    public function update(int $id, array $data): bool
    {
        $existing = $this->findById($id);
        if ($existing === null) {
            return false;
        }

        $nombre = $data['nombre'] !== '' ? $data['nombre'] : $existing['Nombre'];
        $apellido = $data['apellido'] !== '' ? $data['apellido'] : $existing['Apellido'];
        $fechaIngreso = $data['fecha_ingreso'] !== '' ? $data['fecha_ingreso'] : $existing['Fecha_Ingreso'];
        $fechaNacimiento = $data['fecha_nacimiento'] !== '' ? $data['fecha_nacimiento'] : $existing['Fecha_Nacimiento'];
        $calle = $data['calle'] !== '' ? $data['calle'] : $existing['Calle'];
        $numero = $data['numero'] !== '' ? (int) $data['numero'] : (int) $existing['Número'];
        $colonia = $data['colonia'] !== '' ? $data['colonia'] : $existing['Colonia'];
        $ciudad = $data['ciudad'] !== '' ? $data['ciudad'] : $existing['Ciudad'];
        $estado = $data['estado'] !== '' ? $data['estado'] : $existing['Estado'];
        $codigoPostal = $data['codigo_postal'] !== '' ? $data['codigo_postal'] : $existing['Código_Postal'];
        $telefono = $data['telefono'] !== '' ? $data['telefono'] : $existing['Teléfono'];
        $rol = $data['rol'] !== '' ? $data['rol'] : $existing['Rol'];

        $stmt = $this->db->prepare(
            'UPDATE empleado
             SET Nombre = ?, Apellido = ?, Fecha_Ingreso = ?, Fecha_Nacimiento = ?,
                 Calle = ?, Número = ?, Colonia = ?, Ciudad = ?, Estado = ?,
                 Código_Postal = ?, Teléfono = ?, Rol = ?
             WHERE Id_Empleado = ?'
        );
        $stmt->bind_param(
            'sssssisssisss',
            $nombre,
            $apellido,
            $fechaIngreso,
            $fechaNacimiento,
            $calle,
            $numero,
            $colonia,
            $ciudad,
            $estado,
            $codigoPostal,
            $telefono,
            $rol,
            $id
        );

        return $stmt->execute();
    }

    public function isInUse(int $id): bool
    {
        $checks = [
            'SELECT 1 FROM salida WHERE Id_Empleado = ? LIMIT 1',
            'SELECT 1 FROM entrada_producto WHERE Id_Empleado = ? LIMIT 1',
        ];

        foreach ($checks as $sql) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                return true;
            }
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM empleado WHERE Id_Empleado = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
