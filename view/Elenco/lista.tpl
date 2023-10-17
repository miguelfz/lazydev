<section class="lista elenco">
    <h2>Elencos</h2>
    <div>
        <a href="{PATH}/Elenco/cadastrar" class="cadastrar">&plus; cadastrar</a>
    </div>
    {foreach $elencos as $e}
        <div class="item">
            <a href="{PATH}/Elenco/ver/{$e->cod_filme}/{$e->cod_ator}/" class="">{$e->cod_filme}</a>
            <a href="{PATH}/Elenco/excluir/{$e->cod_filme}/{$e->cod_ator}/" title="excluir" class="excluir">&#10006;</a>
            <a href="{PATH}/Elenco/editar/{$e->cod_filme}/{$e->cod_ator}/" title="editar" class="editar">&#9998;</a>
        </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</section>