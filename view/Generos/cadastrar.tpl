<form method="post" class="editar generos">
    <h1>Cadastrar Generos</h1>
    <div>
        <label for="nome_genero">Nome</label>
        <input type=text name="nome_genero" id="nome_genero" value="{$generos->nome_genero}"  required>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>