<h1>Configurando {$tableName}</h1>
<form method="post">
    {* especificações semânticas *}
    <div style="padding:10px; border:1px dotted #CCC;margin:10px">
        <h3>Especificações Gerais:</h3>
        <table>
            <tr>
                <th style="text-align: left;">Campo</td>
                <th style="text-align: left;">Label</th>
                <th style="text-align: left;">Finalidade</th>
            </tr>
            {foreach $tableSchema as $key => $m}
                {if $key neq 'pk' and  $key neq 'fk'}
                    <tr>
                        {if $m->fk neq '0'}
                            <td width="20%" style="color:red">
                                <label for="tipo_{$tableName}_{$m->Field}"><strong>{$m->Field} {$m->Key} (FK)</strong></label>
                                <div><small>{$m->Type}</small></div>
                            </td>
                            <td>
                                <input type="text" spellcheck="true" value="{$m->fk}" name="label_{$tableName}_{$m->Field}">
                            </td>
                            <td>
                                <select name="tipo_{$tableName}_{$m->Field}" id="finalidade">
                                    <option value="selectFK">Select option</option>
                                    <option value="radioFK">Input radio</option>
                                </select>
                            </td>
                        {else}
                            <td width="20%">
                                <label for="tipo_{$tableName}_{$m->Field}"><strong>{$m->Field} {$m->Key}</strong></label>
                                <div><small>{$m->Type}</small></div>
                            </td>
                            <td>
                                <input type="text" spellcheck="true" value="{$m->Field}" name="label_{$tableName}_{$m->Field}">
                            </td>
                            <td>
                                <select name="tipo_{$tableName}_{$m->Field}" id="finalidade">
                                    <option value="text">linha de texto geral</option>
                                    <option value="html" {if $m->InputType eq 'html'}selected{/if}>texto longo com HTML</option>
                                    <option value="number" {if $m->InputType eq 'number'}selected{/if}>número</option>
                                    <option value="now" {if $m->InputType eq 'now'}selected{/if}>agora (data e hora atual)
                                    </option>
                                    <option value="date" {if $m->InputType eq 'date'}selected{/if}>data</option>
                                    <option value="datetime" {if $m->InputType eq 'datetime'}selected{/if}>data e hora</option>
                                    <option value="time" {if $m->InputType eq 'time'}selected{/if}>hora minuto e segundo
                                    </option>
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

    {* especificações página ver *}
    <div style="padding:10px; border:1px dotted #CCC;margin:10px">
        <h3>Página ver</h3>
        <p>Defina o que será exibido na página de visualização de um registro desta tabela.</p>
        <table>
            <tr>
                <th>Exibir?</th>
                <th style="text-align: left;">Atributo</th>
                <th style="text-align: left;">Informação Vinculada</th>
            </tr>
            {foreach $tableSchema as $key => $m}
                {if $key neq 'pk' and  $key neq 'fk'}
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="ver_{$tableName}_{$m->Field}" id="ver_{$tableName}_{$m->Field}" checked>
                        </td>
                        <td>
                            <label for="ver_{$tableName}_{$m->Field}" id="ver_{$tableName}_{$m->Field}">{$m->Field}</label>
                        </td>
                        <td>
                            {if $m->fk neq '0'}
                                <select name="verRef_{$tableName}_{$m->Field}" id="">
                                    {foreach $dbSchema[$m->fk] as $keyfk=> $fk}
                                        {if $keyfk neq 'pk' and  $keyfk neq 'fk'}
                                            <option value="{$fk->Field}">{$m->fk}.{$fk->Field}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            {else}
                                {$tableName}.{$m->Field}
                            {/if}
                        </td>
                    </tr>
                {/if}
            {/foreach}
            {* incluir coleções *}
            {foreach $dbSchema as $key => $m}
                {foreach $m['fk'] as $fk}
                    {if $fk->reftable eq $tableName}
                        <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="verLista_{$key}_{$m->Field}" id="verLista_{$key}_{$m->Field}" checked>
                        </td>
                        <td>
                            <label for="ver_{$tableName}_{$m->Field}" id="ver_{$tableName}_{$m->Field}">
                            Lista de {$fk->table}(s)</label>
                        </td>
                        <td>
                        </td>
                        </tr>
                    {/if}
                {/foreach}
            {/foreach}
        </table>
    </div>

    {* especificações página listar *}
    <div style="padding:10px; border:1px dotted #CCC;margin:10px">
        <h3>Página listar</h3>
    </div>
    {* botão salvar *}
    <div>
        <input type="submit" value="Salvar">
    </div>
    <hr>
</form>