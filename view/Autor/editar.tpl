<h1>Editar Autor</h1>
<form method="post">
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