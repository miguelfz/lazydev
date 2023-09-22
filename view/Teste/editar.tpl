<form method="post" class="editar teste">
    <h1>Editar Teste</h1>
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
        <input type="submit" value="salvar">
    </div>
</form>