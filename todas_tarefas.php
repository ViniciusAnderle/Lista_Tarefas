<?php

$tarefas = []; // Inicializa a variável $tarefas como um array vazio

$acao = 'recuperar';
require './tarefa_controller.php';

// Função de comparação para ordenar alfabeticamente
function compararTarefas($a, $b)
{
    return strcmp($a->tarefa, $b->tarefa);
}

// Função de comparação para ordenar por data de criação
function compararDatas($a, $b)
{
    return strtotime($a->data_cadastrado) - strtotime($b->data_cadastrado);
}

// Função de comparação para ordenar por prioridade
function compararPrioridades($a, $b)
{
    return intval($a->prioridade) - intval($b->prioridade);
}

// Verifica a opção de ordenação selecionada
$ordenacao = ($_GET['ordenacao'] ?? '');

// Filtrar por pendentes, realizadas, arquivadas ou todas
$filtro = ($_GET['filtro'] ?? 'todas');

// Filtrar as tarefas de acordo com o filtro selecionado
$tarefasFiltradas = [];
if ($filtro === 'pendentes') {
    $tarefasFiltradas = array_filter($tarefas, function ($tarefa) {
        return $tarefa->status === 'pendente';
    });
} else if ($filtro === 'realizado') {
    $tarefasFiltradas = array_filter($tarefas, function ($tarefa) {
        return $tarefa->status === 'realizado';
    });
} else if ($filtro === 'vencidas') {
    $tarefasFiltradas = array_filter($tarefas, function ($tarefa) {
        return $tarefa->status === 'pendente' && strtotime($tarefa->prazo) < time();
    });
} else {
    $tarefasFiltradas = $tarefas;
}

// Ordenar o array de tarefas filtradas de acordo com a opção selecionada
if ($ordenacao === 'data_cadastrado') {
    usort($tarefasFiltradas, 'compararDatas');
} else if ($ordenacao === 'prioridade') {
    usort($tarefasFiltradas, 'compararPrioridades');
} else {
    // Default: ordenar alfabeticamente
    usort($tarefasFiltradas, 'compararTarefas');
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

        function arquivar(id) {
            location.href = 'todas_tarefas.php?acao=arquivar&id=' + id;
        }
    </script>
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
                    <li class="list-group-item active"><a href="#">Todas tarefas</a></li>
                    <li class="list-group-item"><a href="arquivadas.php">Tarefas Arquivadas</a></li>

                </ul>
            </div>

            <div class="col-sm-9">
                <div class="container pagina">
                    <div class="row">
                        <div class="col">
                            <h4>Todas tarefas</h4>
                            <hr />

                            <form id="formFiltro" method="get">
                                <label for="filtro">Filtrar por:</label>
                                <select id="filtro" name="filtro" onchange="this.form.submit()">
                                    <option value="todas" <?php echo $filtro === 'todas' ? 'selected' : ''; ?>>Todas</option>
                                    <option value="pendentes" <?php echo $filtro === 'pendentes' ? 'selected' : ''; ?>>Pendentes</option>
                                    <option value="realizado" <?php echo $filtro === 'realizado' ? 'selected' : ''; ?>>Realizadas</option>
                                    <option value="vencidas" <?php echo $filtro === 'vencidas' ? 'selected' : ''; ?>>Vencidas</option>
                                </select>
                            </form>

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
                                    <?php foreach ($tarefasFiltradas as $tarefa) : ?>
                                        <tr>
                                            <td id="tarefa_<?php echo $tarefa->id; ?>"><?php echo $tarefa->tarefa; ?></td>
                                            <td><?php echo $tarefa->categoria; ?></td>
                                            <td><?php echo $tarefa->prazo ? date('d/m/Y', strtotime($tarefa->prazo)) : 'Sem prazo'; ?></td>
                                            <td><?php echo date('d/m/Y H:i:s', strtotime($tarefa->data_cadastrado)); ?></td>
                                            <td>
                                                <button class="btn btn-warning" onclick="editar(<?php echo $tarefa->id; ?>, '<?php echo $tarefa->tarefa; ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" onclick="remover(<?php echo $tarefa->id; ?>)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <?php if ($tarefa->status === 'pendente') : ?>
                                                    <button class="btn btn-success" onclick="marcarRealizada(<?php echo $tarefa->id; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-primary" onclick="arquivar(<?php echo $tarefa->id; ?>)">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php if ($tarefa->prazo && strtotime($tarefa->prazo) > time() && (strtotime($tarefa->prazo) - time() <= 86400)) : ?>
                                            <script>
                                                setTimeout(function() {
                                                    alert("A tarefa '<?php echo $tarefa->tarefa; ?>' está a um dia do vencimento!");
                                                }, 15);
                                            </script>
                                        <?php elseif ($tarefa->prazo && strtotime($tarefa->prazo) < time()) : ?>
                                            <script>
                                                setTimeout(function() {
                                                    alert("A tarefa '<?php echo $tarefa->tarefa; ?>' está a um dia do vencimento!");
                                                }, 15);
                                            </script>
                                    <?php endif;
                                    endforeach;
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>