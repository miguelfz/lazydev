<div class="lista elenco">
    <h2>Elencos</h2>
    {foreach $elencos as $e}
        <div> <a href="{PATH}/Elenco/ver/{$e->cod_filme}/{$e->cod_ator}/">{$e->cod_filme}</a> </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</div>