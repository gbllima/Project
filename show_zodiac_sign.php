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
            echo "<p>Formato de data inválido: " . htmlspecialchars($data_nascimento) . "</p>";
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

                // Verificar se a data de nascimento está dentro do intervalo
                if ($dataInicio <= $data_nascimento_obj && $data_nascimento_obj <= $dataFim) {
                    $signo_encontrado = $signo;
                    break;
                }
            } catch (Exception $e) {
                echo "<p>Erro ao processar as datas dos signos: " . htmlspecialchars($e->getMessage()) . "</p>";
                exit;
            }
        }

        if ($signo_encontrado) {
            echo "<div class='result'>";
            echo "<h2>Seu Signo: " . htmlspecialchars($signo_encontrado->signoNome) . "</h2>";
            echo "<p>" . htmlspecialchars($signo_encontrado->descricao) . "</p>";
            echo "</div>";
        } else {
            echo "<p>Não foi possível determinar seu signo.</p>";
        }
    } else {
        echo "<p>Por favor, preencha o formulário.</p>";
    }
    ?>
    <a href="index.php" class="btn btn-secondary">Voltar</a>
</div>

<?php include('layouts/footer.php'); ?>