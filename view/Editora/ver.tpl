<div class="ver editora">
    <h1>{$editora->nome}</h1>

    <div>cod: {$editora->cod}</div>
    
    {* lista de livro*}
    {include file='../Livro/lista.tpl'}
    
    {* lista de categoria*}
    {include file='../Categoria/lista.tpl'}
</div>