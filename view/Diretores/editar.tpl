<form method="post" class="editar diretores">
    <h1>Editar Diretores</h1>
    <div>
        <label for="nome_diretor">Nome</label>
        <input type=text name="nome_diretor" id="nome_diretor" value="{$diretores->nome_diretor}"  required>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>