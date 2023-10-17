<section class="lista diretores">
    <h2>Diretores</h2>
    <div>
        <a href="{PATH}/Diretores/cadastrar" class="cadastrar">&plus; cadastrar</a>
    </div>
    {foreach $diretoress as $d}
        <div class="item">
            <a href="{PATH}/Diretores/ver/{$d->cod_diretor}/" class="lista">{$d->nome_diretor}</a>
            <a href="{PATH}/Diretores/excluir/{$d->cod_diretor}/" title="excluir" class="excluir">&#10006;</a>
            <a href="{PATH}/Diretores/editar/{$d->cod_diretor}/" title="editar" class="editar">&#9998;</a>
        </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</section>