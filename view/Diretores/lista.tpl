<div class="lista diretores">
    <h2>Diretores</h2>
    {foreach $diretoress as $d}
        <div> <a href="{PATH}/Diretores/ver/{$d->cod_diretor}/">{$d->nome_diretor}</a> </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</div>