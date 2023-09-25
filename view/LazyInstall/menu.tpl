<h1>Instalar menu de navegação</h1>
<form action="" method="post">
    <div class="">
        {foreach $menu as $t}
            <div style="margin: 15px;">
                <input type="checkbox" name="menu[]" id="menu_{$t}" value="{$t}" checked>
                <label for="menu_{$t}">{$t}</label>
            </div>
        {foreachelse}
            <p>Nenhuma tabela encontrada. Verifique o arquivo /config</p>
        {/foreach}
    </div>
    <div class="">
        <input type="submit" value="salvar">
    </div>
</form>
<hr>