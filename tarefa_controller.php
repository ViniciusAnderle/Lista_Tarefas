<?php

require "tarefa.model.php";
require "tarefa.service.php";
require "conexao.php";

$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;

if ($acao == 'inserir') {
    $tarefa = new Tarefa();
    $tarefa->__set('tarefa', $_POST['tarefa']);
    $tarefa->__set('prioridade', $_POST['prioridade']);
    $tarefa->__set('prazo', $_POST['prazo']); // Adiciona o prazo
    $tarefa->__set('categoria', $_POST['categoria']); 


    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->inserir();

    header('Location: nova_tarefa.php?inclusao=1');
} else if ($acao == 'recuperar') {

    $tarefa = new Tarefa();
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperar();
} else if ($acao == 'atualizar') {

    $tarefa = new Tarefa();
    $tarefa->__set('id', $_POST['id'])
        ->__set('tarefa', $_POST['tarefa']);

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    if ($tarefaService->atualizar()) {

        if (isset($_GET['pag']) && $_GET['pag'] == 'index') {
            header('location: index.php');
        } else {
            header('location: todas_tarefas.php');
        }
    }
} else if ($acao == 'remover') {

    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id']);

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->remover();

    if (isset($_GET['pag']) && $_GET['pag'] == 'index') {
        header('location: index.php');
    } else {
        header('location: todas_tarefas.php');
    }
} else if ($acao == 'marcarRealizada') {

    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id'])->__set('id_status', 2);

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->marcarRealizada();

    if (isset($_GET['pag']) && $_GET['pag'] == 'index') {
        header('location: index.php');
    } else {
        header('location: todas_tarefas.php');
    }
} else if ($acao == 'recuperarTarefasPendentes') {
    $tarefa = new Tarefa();
    $tarefa->__set('id_status', 1); // Correção: O campo é 'id_status'

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperarTarefasPendentes();
} else if ($acao == 'arquivar') {

    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id'])->__set('status', 'arquivada');

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->arquivar();

    header('Location: todas_tarefas.php');
    exit; // Encerra o script após o redirecionamento
}

?>
