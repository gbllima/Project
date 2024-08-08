<?php include('layouts/header.php'); ?>

<div class="container mt-5">
    <?php
    if (isset($_POST['data_nascimento']) && !empty($_POST['data_nascimento'])) {
        $data_nascimento = trim($_POST['data_nascimento']); // Remover espaços em branco
        $signos = simplexml_load_file("signos.xml");

        function dataParaObjeto($data, $ano) {
            $dataFormatada = $data . '/' . $ano;
            $objeto = DateTime::createFromFormat('d/m/Y', $dataFormatada);
            if (!$objeto || $objeto->format('d/m/Y') !== $dataFormatada) {
                throw new Exception("Formato de data inválido: $dataFormatada");
            }
            return $objeto;
        }

        // Criar um objeto DateTime para a data de nascimento
        try {
            $data_nascimento_obj = new DateTime($data_nascimento);
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Formato de data inválido: " . htmlspecialchars($data_nascimento) . "</div>";
            exit;
        }

        $ano_nascimento = $data_nascimento_obj->format('Y');
        $signo_encontrado = false;

        foreach ($signos->signo as $signo) {
            try {
                $dataInicio = dataParaObjeto((string) $signo->dataInicio, $ano_nascimento);
                $dataFim = dataParaObjeto((string) $signo->dataFim, $ano_nascimento);

                // Ajustar a data final para o próximo ano, se necessário
                if ($dataFim < $dataInicio) {
                    $dataFim->modify('+1 year');
                }
                $dataInicio->modify('00:00:00');
                $dataFim->modify('23:59:59');

                // Verificar se a data de nascimento está dentro do intervalo.
                if ($dataInicio <= $data_nascimento_obj && $data_nascimento_obj <= $dataFim) {
                    $signo_encontrado = $signo;
                    break;
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>Erro ao processar as datas dos signos: " . htmlspecialchars($e->getMessage()) . "</div>";
                exit;
            }
        }

        if ($signo_encontrado) {
            echo "<div class='result text-center'>";
            echo "<h2 class='display-4'>Seu Signo: " . htmlspecialchars($signo_encontrado->signoNome) . "</h2>";
            echo "<p class='lead'>" . htmlspecialchars($signo_encontrado->descricao) . "</p>";
            echo "<img src='" . htmlspecialchars($signo_encontrado->imagem) . "' class='img-fluid mt-4' alt='Imagem de " . htmlspecialchars($signo_encontrado->signoNome) . "'>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning'>Não foi possível determinar seu signo.</div>";
        }
    } else {
        echo "<div class='alert alert-info'>Por favor, preencha o formulário.</div>";
    }
    ?>
    <a href="index.php" class="btn btn-secondary mt-4">Voltar</a>
</div>

