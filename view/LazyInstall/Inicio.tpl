<h1>Bem vindo ao Instalador do LazyDev</h1>
<p>O instalador do <em>LazyDev</em> permite a geração automática dos models, controllers e views a partir da estrutura
    de sua base de dados, facilitando o início do desenvolvimento de seu sistema.</p>

<h5>Antes de instalar, leia estas recomendações:</h5>
<ul>
    <li>Verifique atentamente o arquivo /config.php;</li>
    <li>Verifique as chaves estrangeiras - Eventuais mapeamentos serão gerados a partir delas;</li>
    <li>Verifique as permissões de escrita nos diretórios;</li>
    <li>Importante: Desabilite o modo <em>debug</em> após a instalação.</li>
</ul>

<hr>
<h2>Tabelas</h2>
<div class="">
    {foreach $tables as $t}
        <div style="padding:10px; border:1px dotted #CCC;">
            <h3><a href="{PATH}/LazyInstall/table/{$t->name}">{$t->name}</a></h3>
        </div>
    {foreachelse}
        <p>Nenhuma tabela encontrada. Verifique o arquivo /config</p>
    {/foreach}
    <div style="padding:10px; border:1px dotted #CCC;">
        <h3><a href="{PATH}/LazyInstall/menu">Menu de navegação</a></h3>
    </div>
</div>
<hr>