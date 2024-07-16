<?php

use App\Core\Model;

class Preco {
    public $id;
    public $primeiraHora;
    public $demaisHoras;

    public function getLastInsert() {

        $sql = 'SELECT * FROM tbPreco ORDER BY id DESC LIMIT 1';

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
            return $resultado;
        } else {
            return NULL;
        }
    }

    public function insertValue() {
        $sql = 'INSERT INTO tbPreco (primeiraHora, demaisHoras) VALUES (:pHora, :dHora)';

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':pHora', $this->primeiraHora);
        $stmt->bindParam(':dHora', $this->demaisHoras);

        if ($stmt->execute()) {
            $this->id = Model::getConn()->lastInsertId();
            return $this;
        } else {
            print_r($stmt->errorInfo());
            return NULL;
        }
    }

    public function queryById($id) {
        $sql = 'SELECT * FROM tbPreco WHERE id = ?';

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);

        if ($stmt->execute()) {
            $preco = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$preco) {
                return NULL;
            }

            $this->id = $preco->id;
            $this->primeiraHora = $preco->primeiraHora;
            $this->demaisHoras = $preco->demaisHoras;

            return $this;
        } else {
            return NULL;
        }
    }
}