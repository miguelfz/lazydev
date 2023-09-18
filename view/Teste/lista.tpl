<h2>Testes</h2>
{foreach $testes as $t}
    <div> <a href="{PATH}/Teste/ver/{$t->cod}/">{$t->cod}</a> </div>
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}