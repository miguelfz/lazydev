<form method="post" class="editar filmes">
    <h1>Cadastrar Filmes</h1>
    <div>
        <label for="titulo_original">Título Original</label>
        <input type=text name="titulo_original" id="titulo_original" value="{$filmes->titulo_original}"  required>
    </div>
    <div>
        <label for="titulo">Título</label>
        <input type=text name="titulo" id="titulo" value="{$filmes->titulo}"  required>
    </div>
    <div>
        <label for="duracao">Duração</label>
        <input type=number name="duracao" id="duracao" value="{$filmes->duracao}" >
    </div>
    <div>
        <label for="ano_lancamento">Ano</label>
        <input type=text name="ano_lancamento" id="ano_lancamento" value="{$filmes->ano_lancamento}"  required>
    </div>
    <div>
        <label for="cod_diretor">Diretor</label>
        <select name="cod_diretor" id="cod_diretor">
            <option></option>
            {html_options options=$diretoress selected=$filmes->cod_diretor}
        </select>
    </div>
    <div>
        <label for="cod_genero">Gênero</label>
        <select name="cod_genero" id="cod_genero">
            <option></option>
            {html_options options=$geneross selected=$filmes->cod_genero}
        </select>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>