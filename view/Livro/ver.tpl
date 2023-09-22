<div class="ver livro">
    <h1>{$livro->titulo}</h1>

    <div>cod: {$livro->cod}</div>
    <div>edicao: {$livro->edicao}</div>
    <div>idioma: {$livro->idioma}</div>
    <div>exemplares: {$livro->exemplares}</div>
    <div>paginas: {$livro->paginas}</div>
    <div>lido: {$livro->lido}</div>
    <div>categoria: <a href="{PATH}/Categoria/ver/{$livro->getCategoria()->cod}">{$livro->getCategoria()->nome}</a></div>
    <div>editora: <a href="{PATH}/Editora/ver/{$livro->getEditora()->cod}">{$livro->getEditora()->nome}</a></div>
    
    {* lista de livroautor*}
    {include file='../Livroautor/lista.tpl'}
    
    {* lista de autor*}
    {include file='../Autor/lista.tpl'}
</div>