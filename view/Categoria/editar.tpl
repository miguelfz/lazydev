<form method="post" class="editar categoria">
    <h1>Editar Categoria</h1>
    <div>
        <label for="nome">nome</label>
        <input type=text name="nome" id="nome" value="{$categoria->nome}"  required>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>