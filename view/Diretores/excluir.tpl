<form method="post" class="excluir diretores">
    <h1>Excluir {$diretores->nome_diretor}</h1>

    <p>Você tem certeza que deseja excluir este registro?</p>

    <input type="hidden" name="cod_diretor" value="{$diretores->cod_diretor}">
    <input type="submit" value="Sim">
    <a href="{PATH}/Diretores/lista">cancelar</a>
</form>