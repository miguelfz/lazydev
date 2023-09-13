<h1>Lista de Livro</h1>
{foreach $livros as $l}
    <p>{$l->titulo}</p>
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}