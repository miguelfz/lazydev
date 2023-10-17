<form method="post" class="excluir usuario">
    <h1>Excluir {$usuario->email}</h1>
    <p>Você tem certeza que deseja excluir este registro?</p>
    <input type="hidden" name="cod" value="{$usuario->cod}">
    <input type="submit" value="Sim">
    <a href="{PATH}/Usuario/lista">cancelar</a>
</form>