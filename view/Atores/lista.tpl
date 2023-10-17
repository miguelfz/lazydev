<section class="lista atores">
    <h2>Atores</h2>
    <div>
        <a href="{PATH}/Atores/cadastrar" class="cadastrar">&plus; cadastrar</a>
    </div>
    {foreach $atoress as $a}
        <div class="item">
            <a href="{PATH}/Atores/ver/{$a->cod_ator}/" class="lista">{$a->nome_ator}</a>
            <a href="{PATH}/Atores/excluir/{$a->cod_ator}/" title="excluir" class="excluir">&#10006;</a>
            <a href="{PATH}/Atores/editar/{$a->cod_ator}/" title="editar" class="editar">&#9998;</a>
        </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</section>