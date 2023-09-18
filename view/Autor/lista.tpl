<h2>Autores</h2>
{foreach $autors as $a}
    <div> <a href="{PATH}/Autor/ver/{$a->cod}/">{$a->nome}</a> </div>
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}