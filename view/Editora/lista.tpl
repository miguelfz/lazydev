<div class="lista editora">
    <h2>Editoras</h2>
    {foreach $editoras as $e}
        <div> <a href="{PATH}/Editora/ver/{$e->cod}/">{$e->nome}</a> </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</div>