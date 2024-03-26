<?php
require "conexao.php";
require "tarefa.model.php";
require "tarefa.service.php";
$acao = '';
$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;

$tarefa = new Tarefa();
$conexao = new Conexao();
$tarefaService = new TarefaService($conexao, $tarefa);

$tarefas = $tarefaService->recuperarArquivadas();

if ($acao == 'desarquivar' && isset($_GET['id'])) {
    $tarefa->__set('id', $_GET['id']);
    $tarefaService->desarquivar($_GET['id']);
    header('Location: arquivadas.php');
    exit;
}

?>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Lista Tarefas</title>

    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">


</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
                App Lista Tarefas
            </a>
        </div>
    </nav>

    <div class="container app">
        <div class="row">
            <div class="col-sm-3 menu">
                <ul class="list-group">
                    <li class="list-group-item"><a href="index.php">Tarefas pendentes</a></li>
                    <li class="list-group-item"><a href="nova_tarefa.php">Nova tarefa</a></li>
                    <li class="list-group-item "><a href="todas_tarefas.php">Todas tarefas</a></li>
                    <li class="list-group-item active"><a href="arquivadas.php">Tarefas Arquivadas</a></li>

                </ul>
            </div>

            <div class="col-sm-9">
                <div class="container pagina">
                    <div class="row">
                        <div class="col">
                            <h4>Arquivadas</h4>
                            <hr />


                            <br />

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tarefa</th>
                                        <th scope="col">Categoria</th>
                                        <th scope="col">Prazo</th>
                                        <th scope="col">Data de Cadastro</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tarefas as $tarefa) : ?>
                                        <tr>
                                            <td><?= $tarefa->tarefa ?></td>
                                            <td><?= $tarefa->categoria ?></td>
                                            <td><?= $tarefa->prazo ?></td>

                                            <td><?= $tarefa->data_cadastrado ?></td>
                                            <td><?= $tarefa->prioridade ?></td>
                                            <td>
                                                <a href="?acao=desarquivar&id=<?= $tarefa->id ?>"><i class="fas fa-folder-open"></i></a>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>