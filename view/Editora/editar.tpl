<form method="post" class="editar editora">
    <h1>Editar Editora</h1>
    <div>
        <label for="nome">nome</label>
        <input type=text name="nome" id="nome" value="{$editora->nome}"  required>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>