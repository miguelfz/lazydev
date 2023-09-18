<h1>Autor2s</h1>
{foreach $autor2s as $a}
    <p><a href="{PATH}/Autor2/ver/nome:{$a->nome}/">
    {$a->nome}</a></p>
{foreachelse}
    <p>Nada para exibir aqui.</p>
{/foreach}