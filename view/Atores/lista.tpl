<div class="lista atores">
    <h2>Atores</h2>
    {foreach $atoress as $a}
        <div> <a href="{PATH}/Atores/ver/{$a->cod_ator}/">{$a->nome_ator}</a> </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</div>