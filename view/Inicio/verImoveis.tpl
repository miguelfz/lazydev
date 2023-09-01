<h1>Imóveis de {$municipio->nome}</h1>
{foreach $imoveis as $i}
    <p>{$i->nome}</p>
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}
{$imoveis->getNav()}
<hr>
<a href="{PATH}/Inicio/inicio/p:{$p}">voltar</a>
