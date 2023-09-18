<h2>Livros</h2>
{foreach $livros as $l}
    <div> <a href="{PATH}/Livro/ver/{$l->cod}/">{$l->titulo}</a> </div>
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}