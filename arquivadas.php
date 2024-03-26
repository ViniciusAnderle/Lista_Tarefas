<?php
require "tarefa.model.php";
require "tarefa.service.php";
require "conexao.php";

$tarefa = new Tarefa();
$conexao = new Conexao();

$tarefaService = new TarefaService($conexao, $tarefa);
$tarefas = $tarefaService->recuperarArquivadas();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'desarquivar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conexao = new Conexao();
    $tarefa = new Tarefa();
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->desarquivar($id);
    echo 'Tarefa desarquivada com sucesso!';
}

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
                    <li class="list-group-item"><a href="todas_tarefas.php">Todas Tarefas</a></li>
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
                                        - Prazo: <?= !empty($tarefa->prazo) ? date('d/m/Y', strtotime($tarefa->prazo)) : 'Não definido' ?>
                                    </div>
                                    <div class="col-sm-3 mt-2 d-flex justify-content-between">
                                        <i class="fas fa-trash-alt fa-lg text-danger" onclick="remover(<?= $tarefa->id ?>)"></i>

                                        <?php if ($tarefa->status === 'pendente'): ?>
                                            <i class="fas fa-check-square fa-lg text-success" onclick="marcarRealizada(<?= $tarefa->id ?>)"></i>
                                        <?php endif; ?>

                                        <i class="fas fa-edit fa-lg text-info" onclick="editar(<?= $tarefa->id ?>, '<?= $tarefa->tarefa ?>')"></i>

                                        <i class="fas fa-archive fa-lg text-warning" onclick="desarquivarTarefa(<?= $tarefa->id ?>)"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>
        function editar(id, txt_tarefa) {

            //criar um form de edição
            let form = document.createElement('form')
            form.action = 'tarefa_controller.php?acao=atualizar'
            form.method = 'post'
            form.className = 'row'

            //criar um input para entrada do texto
            let inputTarefa = document.createElement('input')
            inputTarefa.type = 'text'
            inputTarefa.name = 'tarefa'
            inputTarefa.className = 'col-9 form-control'
            inputTarefa.value = txt_tarefa

            //criar um input hidden para guardar o id da tarefa
            let inputId = document.createElement('input')
            inputId.type = 'hidden'
            inputId.name = 'id'
            inputId.value = id

            //criar um button para envio do form
            let button = document.createElement('button')
            button.type = 'submit'
            button.className = 'col-3 btn btn-info'
            button.innerHTML = 'Atualizar'

            //incluir inputTarefa no form
            form.appendChild(inputTarefa)

            //incluir inputId no form
            form.appendChild(inputId)

            //incluir button no form
            form.appendChild(button)

            //teste

            //teste
            //console.log(form)

            //selecionar a div tarefa
            let tarefa = document.getElementById('tarefa_' + id)

            //limpar o texto da tarefa para inclusão do form
            tarefa.innerHTML = ''

            //incluir form na página
            tarefa.insertBefore(form, tarefa.firstChild)

        }

        function remover(id) {
            location.href = 'todas_tarefas.php?acao=remover&id=' + id;
        }

        function marcarRealizada(id) {
            location.href = 'todas_tarefas.php?acao=marcarRealizada&id=' + id;
        }

        function desarquivarTarefa(id) {
        // enviar requisição AJAX para desarquivar a tarefa
        $.ajax({
            type: "GET",
            url: "arquivadas.php",
            data: {acao: "desarquivar", id: id},
            success: function(response) {
                alert(response); // exibir mensagem de sucesso ou erro
                window.location.reload(); // recarregar a página após ação concluída
            }
        });
    }
</script>
</body>
</html>
