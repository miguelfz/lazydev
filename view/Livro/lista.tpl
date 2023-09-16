<h1>Lista de Livro</h1>
{foreach $livros as $l}
    <p>{$l->titulo}</p>
    {*<p><a href="{PATH}/curso/ver/{$c->cod_curso}">{$c->nome}</a></p>*}
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}