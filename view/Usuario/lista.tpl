<section class="lista usuario">
    <h2>Usuarios</h2>
    <div>
        <a href="{PATH}/Usuario/cadastrar" class="cadastrar">&plus; cadastrar</a>
    </div>
    {foreach $usuarios as $u}
        <div class="item">
            <a href="{PATH}/Usuario/ver/{$u->cod}/" class="">{$u->email}</a>
            <a href="{PATH}/Usuario/excluir/{$u->cod}/" title="excluir" class="excluir">&#10006;</a>
            <a href="{PATH}/Usuario/editar/{$u->cod}/" title="editar" class="editar">&#9998;</a>
        </div>
    {foreachelse}
        <p>Nada para exibir aqui.</p>
    {/foreach}
</section>