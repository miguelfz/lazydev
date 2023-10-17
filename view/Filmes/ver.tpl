<div class="ver filmes">
    <h1>{$filmes->titulo_original}</h1>

    <div>cod_filme: {$filmes->cod_filme}</div>
    <div>Título: {$filmes->titulo}</div>
    <div>Duração: {$filmes->duracao}</div>
    <div>Ano Lançamento: {$filmes->ano_lancamento}</div>
    <div>Gênero: <a href="{PATH}/Generos/ver/{$filmes->getGeneros()->cod_genero}">{$filmes->getGeneros()->nome_genero}</a></div>
    
    {* lista de elenco*}
    {include file='../Elenco/lista.tpl'}
    
    {* lista de atores*}
    {include file='../Atores/lista.tpl'}
</div>