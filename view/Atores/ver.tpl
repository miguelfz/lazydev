<section class="ver atores">
    <h1>{$atores->nome_ator}</h1>

    <div>cod_ator: {$atores->cod_ator}</div>
    
    {* lista de elenco*}
    {include file='../Elenco/lista.tpl'}
    
    {* lista de filmes*}
    {include file='../Filmes/lista.tpl'}
</section>