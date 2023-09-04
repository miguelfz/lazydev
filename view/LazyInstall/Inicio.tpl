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
    {foreach $db as $tableName => $tableSchema}
        <div style="padding:10px; border:1px dotted #CCC;">
            <h3><a href="{PATH}/LazyInstall/model/{$tableName}">{$tableName}</a></h3>
            <table>
                <tr>
                    <th>Campo</td>
                    <th>Label</th>
                    <th>Finalidade</th>
                </tr>
                {foreach $tableSchema as $key => $m}
                    {if $key neq 'pk' and  $key neq 'fk'}
                        <tr>
                            {if $m->fk neq '0'}
                                <td width="20%" style="color:red">
                                    <label for=""><strong>{$m->Field} {$m->Key} (FK)</strong></label>
                                    <div><small>{$m->Type}</small></div>
                                </td>
                                <td>
                                    <input type="text" value="{$m->fk}" name="label_{$tableName}_{$m->Field}">
                                </td>
                                <td>
                                    <select name="tipo_{$tableName}_{$m->Field}" id="finalidade">
                                        <option value="selectFK">Select option</option>
                                        <option value="radioFK">Input radio</option>
                                    </select>
                                </td>
                            {else}
                                <td width="20%">
                                    <label for=""><strong>{$m->Field} {$m->Key}</strong></label>
                                    <div><small>{$m->Type}</small></div>
                                </td>
                                <td>
                                    <input type="text" value="{$m->Field}" name="label_{$tableName}_{$m->Field}">
                                </td>
                                <td>
                                    <select name="tipo_{$tableName}_{$m->Field}" id="finalidade">
                                        <option value="text">linha de texto geral</option>
                                        <option value="html" {if $m->InputType eq 'html'}selected{/if}>texto longo com HTML</option>
                                        <option value="number" {if $m->InputType eq 'number'}selected{/if}>número</option>
                                        <option value="now" {if $m->InputType eq 'now'}selected{/if}>agora (data e hora atual)</option>
                                        <option value="date" {if $m->InputType eq 'date'}selected{/if}>data</option>
                                        <option value="datetime" {if $m->InputType eq 'datetime'}selected{/if}>data e hora</option>
                                        <option value="time" {if $m->InputType eq 'time'}selected{/if}>hora minuto e segundo</option>
                                        <option value="password">senha</option>
                                        <option value="checkbox" {if $m->InputType eq 'checkbox'}selected{/if}>Checkbox (boolean)
                                        </option>
                                        <option value="image">imagem com upload (não blob)</option>
                                        <option value="file">arquivo com upload (não blob)</option>
                                    </select>
                                </td>
                            {/if}
                        </tr>
                    {/if}
                {foreachelse}
                    <p>Nenhum campo encontrado.</p>
                {/foreach}
            </table>
        </div>
    {foreachelse}
        <p>Nenhuma tabela encontrada. Verifique o arquivo /config</p>
    {/foreach}
</div>
<hr>