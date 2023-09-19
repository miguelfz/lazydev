<h1>{$autor->nome}</h1>

<div>cod: {$autor->cod}</div>
<div>pais: {$autor->pais}</div>

{* lista de livroautor*}
{include file='../Livroautor/lista.tpl'}

{* lista de livro*}
{include file='../Livro/lista.tpl'}