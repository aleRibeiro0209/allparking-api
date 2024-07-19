<?php

use App\Core\Model;

class Registro {
    public $id;
    public $nomeCliente;
    public $placaCarro;
    public $dataHoraEntrada;
    public $dataHoraSaida;
    public $valorTotal;
    public $precoId;

    public function listAll(): array {
        $sql = "SELECT * FROM tbRegistro ORDER BY id DESC";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
        } else {
            return [];
        }
    }

    public function insertCostumer() {
        $sql = 'INSERT INTO tbRegistro (nomeCliente, placaCarro, dataHoraEntrada, precoId) VALUES (?, ?, ?, ?)';

        $data = new DateTime($this->dataHoraEntrada);
        $dataFormatada = $data->format('Y-m-d H:i:s');

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $this->nomeCliente);
        $stmt->bindValue(2, $this->placaCarro);
        $stmt->bindValue(3, $dataFormatada);
        $stmt->bindValue(4, $this->precoId);

        if ($stmt->execute()) {
            $this->id = Model::getConn()->lastInsertId();
            return $this;
        } else {
            print_r($stmt->errorInfo());
            return NULL;
        }
    }

    public function queryById($id) {
        $sql = 'SELECT * FROM tbRegistro WHERE id = ?';
        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);

        if ($stmt->execute()) {
            $registro = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$registro) {
                return NULL;
            }

            $this->id = $registro->id;
            $this->nomeCliente = $registro->nomeCliente;
            $this->placaCarro = $registro->placaCarro;
            $this->dataHoraEntrada = $registro->dataHoraEntrada;
            $this->dataHoraSaida = $registro->dataHoraSaida;
            $this->valorTotal = $registro->valorTotal;
            $this->precoId = $registro->precoId;

            return $this;
        } else {
            return NULL;
        }
    }

    public function update() {
        $sql = 'UPDATE tbRegistro SET dataHoraSaida = ?, valorTotal = ? WHERE id = ?';

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $this->dataHoraSaida);
        $stmt->bindValue(2, $this->valorTotal);
        $stmt->bindValue(3, $this->id);

        return $stmt->execute();
    }
}