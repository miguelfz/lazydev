<h1>{$categoria->nome}</h1>

<div>cod: {$categoria->cod}</div>

{* lista de livro*}
{include file='../Livro/lista.tpl'}

{* lista de editora*}
{include file='../Editora/lista.tpl'}