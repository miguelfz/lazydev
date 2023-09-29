<form method="post" class="editar elenco">
    <h1>Cadastrar Elenco</h1>
    <div>
        <label for="cod_filme">filmes</label>
        <select name="cod_filme" id="cod_filme">
            {html_options options=$filmess selected=$elenco->cod_filme}
        </select>
    </div>
    <div>
        <label for="cod_ator">atores</label>
        <select name="cod_ator" id="cod_ator">
            {html_options options=$atoress selected=$elenco->cod_ator}
        </select>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>