<?php

class TarefaService
{
	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao, Tarefa $tarefa)
	{
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	public function inserir() {
		$query = "insert into tb_tarefas(tarefa, prioridade, prazo) values (:tarefa, :prioridade, :prazo)";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
		$stmt->bindValue(':prioridade', $this->tarefa->__get('prioridade'));
		$stmt->bindValue(':prazo', $this->tarefa->__get('prazo')); // Adiciona o bindValue para prazo
		$stmt->execute();
	}

	public function recuperar()
	{
		$query = '
            SELECT 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade, t.arquivada, t.prazo
            FROM 
                tb_tarefas as t
                LEFT JOIN tb_status as s ON (t.id_status = s.id)
        ';
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function atualizar()
	{
		$query = "UPDATE tb_tarefas SET tarefa = :tarefa WHERE id = :id";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
		$stmt->bindValue(':id', $this->tarefa->__get('id'));
		return $stmt->execute();
	}

	public function remover()
	{
		$query = 'DELETE FROM tb_tarefas WHERE id = :id';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id', $this->tarefa->__get('id'));
		$stmt->execute();
	}

	public function marcarRealizada()
	{
		$query = "UPDATE tb_tarefas SET id_status = :id_status WHERE id = :id";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id_status', $this->tarefa->__get('id_status'));
		$stmt->bindValue(':id', $this->tarefa->__get('id'));
		return $stmt->execute();
	}

	public function arquivar()
{
    $query = 'UPDATE tb_tarefas SET arquivada = 1 WHERE id = :id';
    $stmt = $this->conexao->prepare($query);
    $stmt->bindValue(':id', $this->tarefa->__get('id'));
    return $stmt->execute();
}

	public function recuperarTarefasPendentes()
	{
		$query = '
            select 
                t.id, s.status, t.tarefa ,t.data_cadastrado, t.prioridade
                
            from 
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id)
            where
                t.id_status = :id_status
        ';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id_status', $this->tarefa->__get('id_status'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}


	public function recuperarNaoArquivadas()
	{
		$query = '
            SELECT 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade, t.arquivada
            FROM 
                tb_tarefas as t
                LEFT JOIN tb_status as s ON (t.id_status = s.id)
            WHERE 
                t.arquivada = 0
        ';
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function excluirArquivadas()
	{
		$query = 'DELETE FROM tb_tarefas WHERE arquivada = 1';
		$stmt = $this->conexao->prepare($query);
		return $stmt->execute();
	}
}
?>