<h2>Municípios</h2>

<form method="get" action="{PATH}/Inicio/inicio">
    <div>
        <input type="search" name="pesquisa" placeholder="pesquisar">
    </div>
</form>

<p>{$html->getLink('cadastrar', 'Inicio','cadastrar')}</p>
{foreach $municipios as $m}
    <p>
        <a href="{PATH}/Inicio/verImoveis/{$m->id}/{$p}">{$m->nome}</a>
    </p>
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}
{$municipios->getNav()}