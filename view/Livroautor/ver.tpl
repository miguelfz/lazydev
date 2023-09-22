<div class="ver livroautor">
    <h1>{$livroautor->codLivro}</h1>

    <div>livro: <a href="{PATH}/Livro/ver/{$livroautor->getLivro()->cod}">{$livroautor->getLivro()->titulo}</a></div>
    <div>autor: <a href="{PATH}/Autor/ver/{$livroautor->getAutor()->cod}">{$livroautor->getAutor()->nome}</a></div>
    
    {* lista de teste*}
    {include file='../Teste/lista.tpl'}
    
    {* lista de teste*}
    {include file='../Teste/lista.tpl'}
</div>