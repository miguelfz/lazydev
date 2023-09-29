<div class="lista filmes">
    <h2>Filmes</h2>
    {foreach $filmess as $f}
        <div> <a href="{PATH}/Filmes/ver/{$f->cod_filme}/">{$f->titulo}</a> </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</div>