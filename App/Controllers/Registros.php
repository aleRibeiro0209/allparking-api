<?php

use App\Core\Controller;

class Registros extends Controller
{
    
    public function index() {
        $registroModel = $this->model('Registro');

        $registro = $registroModel->listAll();

        echo json_encode($registro, JSON_UNESCAPED_UNICODE);
    }

    public function store() {
        
        $novoRegistro = $this->getRequestBody();

        $registroModel = $this->model('Registro');
        $registroModel->nomeCliente = $novoRegistro->nomeCliente;
        $registroModel->placaCarro = $novoRegistro->placaCarro;
        $registroModel->dataHoraEntrada = $novoRegistro->dataHoraEntrada;

        $precoModel = $this->model('Preco');

        $ultimoPreco = $precoModel->getLastInsert();

        $registroModel->precoId = $ultimoPreco->id;

        $registroModel = $registroModel->insertCostumer();

        if ($registroModel) {
            http_response_code(201); // Created
            echo json_encode($registroModel);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Problemas ao registrar cliente']);
        }
    }

    public function delete($id) {
        $registroModel = $this->model('Registro');

        $registroModel = $registroModel->queryById($id);

        if (!$registroModel) {
            http_response_code(404);
            echo json_encode(["erro" => "Registro não encontrado"]);
            exit;
        }

        $registroModel = $this->calculateValue($registroModel);
        $registroModel->update();

        echo json_encode($registroModel, JSON_UNESCAPED_UNICODE);
    }

    private function calculateValue($registroModel) {
        $dataEntrada = DateTime::createFromFormat('Y-m-d H:i:s', $registroModel->dataHoraEntrada);

        $dataSaida = new DateTime();

        $intervalo = $dataSaida->diff($dataEntrada);

        $horas = 0;

        if ($intervalo->d > 0) {
            $horas += $intervalo->d * 24;
        }

        $horas += $intervalo->h;

        // Tolerância de 10 minutos.
        if ($intervalo->i > 10) {
            $horas += 1;
        }

        $precoModel = $this->model('Preco');
        $precoModel = $precoModel->queryById($registroModel->precoId);
        $registroModel->valorTotal = $precoModel->primeiraHora;

        $horas--;

        if ($horas > 0) {
            $registroModel->valorTotal += $precoModel->demaisHoras * $horas;
        }

        $registroModel->dataHoraSaida = $dataSaida->format('Y-m-d H:i:s');

        return $registroModel;
    }
}
