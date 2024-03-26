<?php
require "tarefa.model.php";
require "tarefa.service.php";
require "conexao.php";

$tarefa = new Tarefa();
$conexao = new Conexao();

$tarefaService = new TarefaService($conexao, $tarefa);
$tarefas = $tarefaService->recuperarArquivadas();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefas Arquivadas</title>
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
                    <li class="list-group-item"><a href="todas_tarefas.php">Tarefas Arquivadas</a></li>
                    <li class="list-group-item active"><a href="arquivadas.php">Tarefas Arquivadas</a></li>
                </ul>
            </div>

            <div class="col-sm-9">
                <div class="container pagina">
                    <div class="row">
                        <div class="col">
                            <h4>Todas tarefas arquivadas</h4>
                            <hr />

                            <?php foreach ($tarefas as $tarefa): ?>
                                <div class="row mb-3 d-flex align-items-center tarefa">
                                    <div class="col-sm-9" id="tarefa_<?= $tarefa->id ?>">
                                        <?= $tarefa->tarefa ?> (<?= $tarefa->status ?>) - Prioridade:
                                        <?php
                                        if ($tarefa->prioridade == 1) {
                                            echo 'Alta';
                                        } elseif ($tarefa->prioridade == 2) {
                                            echo 'Média';
                                        } elseif ($tarefa->prioridade == 3) {
                                            echo 'Baixa';
                                        }
                                        ?>
                                        <br>
                                        Criado em: <?= date('d/m/Y H:i', strtotime($tarefa->data_cadastrado)) ?>
                                        <!-- Exibir o prazo mesmo que esteja vazio -->
                                        - Prazo: <?= !empty($tarefa->prazo) ? date('d/m/Y', strtotime($tarefa->prazo)) : 'Não definido' ?>
                                    </div>
                                    <div class="col-sm-3 mt-2 d-flex justify-content-between">
                                        <i class="fas fa-trash-alt fa-lg text-danger" onclick="remover(<?= $tarefa->id ?>)"></i>

                                        <?php if ($tarefa->status === 'pendente'): ?>
                                            <i class="fas fa-check-square fa-lg text-success" onclick="marcarRealizada(<?= $tarefa->id ?>)"></i>
                                        <?php endif; ?>

                                        <i class="fas fa-edit fa-lg text-info" onclick="editar(<?= $tarefa->id ?>, '<?= $tarefa->tarefa ?>')"></i>

                                        <!-- Adicionando o ícone de desarquivar -->
                                        <i class="fas fa-folder-open fa-lg text-warning" onclick="desarquivar(<?= $tarefa->id ?>)"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
