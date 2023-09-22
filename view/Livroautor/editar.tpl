<form method="post" class="editar livroautor">
    <h1>Editar Livroautor</h1>
    <div>
        <label for="codLivro">livro</label>
        <select name="codLivro" id="codLivro">
            {html_options options=$livros selected=$livroautor->codLivro}
        </select>
    </div>
    <div>
        <label for="codAutor">autor</label>
        <select name="codAutor" id="codAutor">
            {html_options options=$autors selected=$livroautor->codAutor}
        </select>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>