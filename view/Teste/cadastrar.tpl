<h1>Cadastrar Teste</h1>
<form method="post">
    <div>
        <label for="codlivro">livroautor</label>
        <select name="codlivro" id="codlivro">
            {html_options options=$livroautors selected=$teste->codlivro}
        </select>
    </div>
    <div>
        <label for="codAutor">livroautor</label>
        <select name="codAutor" id="codAutor">
            {html_options options=$livroautors selected=$teste->codAutor}
        </select>
    </div>
    <div>
        <input type="submit" value="cadastrar">
    </div>
</form>