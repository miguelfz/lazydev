<h2>Livroautores</h2>
{foreach $livroautors as $l}
    <div> <a href="{PATH}/Livroautor/ver/{$l->codLivro}/{$l->codAutor}/">{$l->codLivro}</a> </div>
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}