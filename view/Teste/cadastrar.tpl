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
                <div>
            {html_radios required='' name='codAutor' options=$livroautors selected=$teste->codAutor separator='<br>'}
                </div>
    </div>
    <div>
        <input type="submit" value="cadastrar">
    </div>
</form>