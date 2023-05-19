<?php

class Vaga
{
    public $pdo;

    private string $cargo;
    private string $descricao;
    private string $cidade;
    private string $estado;
    private string $empresa;
    private string $contato;

    public function __construct()
    {
        require_once __DIR__ . '/../config/config.php';
        $this->pdo = $pdo;

        // $this->cargo = $cargo;
        // $this->descricao = $descricao;
        // $this->cidade = $cidade;
        // $this->empresa = $empresa;
        // $this->setContato($contato);

        // $add = $this->pdo->prepare("INSERT INTO vagas (cargo, descricao, cidade, empresa, contato) VALUES (:cargo, :descricao, :cidade, :empresa, :contato)");
        // $add->execute(array(
        //     ':cargo' => $this->cargo,
        //     ':descricao' => $this->descricao,
        //     ':cidade' => $this->cidade,
        //     ':empresa' => $this->empresa,
        //     ':contato' => $this->contato
        // ));

        // $this->add();
    }

    public function add(string $table, string $cargo, string $empresa, string $descricao, string $cidade, string $estado, string $contato): void
    {
        $this->cargo = $cargo;
        $this->empresa = $empresa;
        $this->descricao = $descricao;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->setContato($contato);

        $add = $this->pdo->prepare("INSERT INTO $table (cargo, empresa, descricao, cidade, estado, contato) VALUES (:cargo, :empresa, :descricao, :cidade, :estado, :contato)");
        $add->execute(array(
            ':cargo' => $this->cargo,
            ':empresa' => $this->empresa,
            ':descricao' => $this->descricao,
            ':cidade' => $this->cidade,
            ':estado' => $this->estado,
            ':contato' => $this->contato
        ));
    }

    public function showAll(string $table): array
    {
        $stmt = $this->pdo->query("SELECT * FROM $table");
        $stmt->execute();
        $allRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $allRows;
    }

    public function findById(string $table, int $id): array
    {
        $query = $this->pdo->prepare("SELECT id, cargo, empresa, descricao, cidade, estado, contato, date_format(data, '%d/%m/%Y') as data FROM $table WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $vaga = $query->fetchAll(PDO::FETCH_ASSOC)[0];

        return $vaga;
    }

    public function delete(string $table, int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getCargo(): string
    {
        return $this->cargo;
    }

    public function setCargo(string $cargo): void
    {
        $this->cargo = $cargo;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    public function setCidade(string $cidade): void
    {
        $this->cidade = $cidade;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getEmpresa(): string
    {
        return $this->empresa;
    }

    public function setEmpresa(string $empresa): void
    {
        $this->empresa = $empresa;
    }

    public function getContato(): string
    {
        return $this->contato;
    }

    public function setContato(string $contato): void
    {
        if ($this->validateContato($contato)) {
            $this->contato = $contato;
            return;
        }
        throw new Exception('Contato inválido! Insira um novo email ou telefone!');
    }

    private function validateContato($emailOrPhone): bool
    {
        $regex = '/^(?:(\((\d{2})\)|0(\d{2}))?\s?\d{4,5}-?\d{4}|\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b)$/';
        return preg_match($regex, $emailOrPhone);
    }
}
