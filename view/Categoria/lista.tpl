<div class="lista categoria">
    <h2>Categorias</h2>
    {foreach $categorias as $c}
        <div> <a href="{PATH}/Categoria/ver/{$c->cod}/">{$c->nome}</a> </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</div>