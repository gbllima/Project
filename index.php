<?php include('layouts/header.php'); ?>

<div class="container mt-5">
    <header>
        <h1>Descubra Seu Signo</h1>
        <img src="assets/imgs/logo.png" alt="Logo do Horóscopo">
    </header>
    
    <form id="signo-form" method="POST" action="show_zodiac_sign.php">
        <h2>Insira sua Data de Nascimento</h2>
        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Descobrir Meu Signo</button>
    </form>
</div>

