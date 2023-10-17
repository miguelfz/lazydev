<form method="post" class="cadastrar diretores">
    <h1>Cadastrar Diretores</h1>
    <div>
        <label for="nome_diretor">Nome</label>
        <input type=text name="nome_diretor" id="nome_diretor" value="{$diretores->nome_diretor}"  required>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>