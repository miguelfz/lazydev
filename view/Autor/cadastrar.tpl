<form method="post" class="editar autor">
    <h1>Cadastrar Autor</h1>
    <div>
        <label for="nome">nome</label>
        <input type=text name="nome" id="nome" value="{$autor->nome}" >
    </div>
    <div>
        <label for="pais">pais</label>
        <input type=text name="pais" id="pais" value="{$autor->pais}" >
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>