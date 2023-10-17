<form method="post" class="excluir atores">
    <h1>Excluir {$atores->nome_ator}</h1>

    <p>Você tem certeza que deseja excluir este registro?</p>

    <input type="hidden" name="cod_ator" value="{$atores->cod_ator}">
    <input type="submit" value="Sim">
    <a href="{PATH}/Atores/lista">cancelar</a>
</form>