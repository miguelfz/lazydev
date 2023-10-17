<form method="post" class="excluir elenco">
    <h1>Excluir {$elenco->cod_filme}</h1>
    <p>Você tem certeza que deseja excluir este registro?</p>
    <input type="hidden" name="cod_filme" value="{$elenco->cod_filme}">
    <input type="hidden" name="cod_ator" value="{$elenco->cod_ator}">
    <input type="submit" value="Sim">
    <a href="{PATH}/Elenco/lista">cancelar</a>
</form>