<div class="lista generos">
    <h2>Generos</h2>
    {foreach $geneross as $g}
        <div> <a href="{PATH}/Generos/ver/{$g->cod_genero}/">{$g->nome_genero}</a> </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</div>