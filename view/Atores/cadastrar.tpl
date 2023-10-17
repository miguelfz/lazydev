<form method="post" class="cadastrar atores">
    <h1>Cadastrar Atores</h1>
    <div>
        <label for="nome_ator">nome_ator</label>
        <input type=text name="nome_ator" id="nome_ator" value="{$atores->nome_ator}"  required>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>