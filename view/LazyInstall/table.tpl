<h1>Configurando {$tableSchema['name']}</h1>
<form method="post">
    <input type="hidden" name="table" value="{$tableSchema['name']}">

    {* especificações arquivos gerados *}
    <div style="padding:30px; border:2px solid #CCC;margin:10px">
        <h3>Arquivos</h3>
        <p>Escolha quais arquivos serão criados/sobrescritos.</p>

        <div style="margin: 15px;">
            <input type="checkbox" name="createmodel" id="createmodel" checked>
            <label for="createmodel" style="font-weight: bold;">/Model/{$tableSchema['name']|capitalize}.php</label>
        </div>
        <div style="margin: 15px;">
            <input type="checkbox" name="createcontroller" id="createcontroller" checked>
            <label for="createcontroller"
                style="font-weight: bold;">/Controller/{$tableSchema['name']|capitalize}.php</label>
        </div>
        <div style="margin: 15px;">
            <input type="checkbox" name="createlista" id="createlista" checked>
            <label for="createlista">/View/{$tableSchema['name']|capitalize}/lista.tpl</label>
        </div>
        <div style="margin: 15px;">
            <input type="checkbox" name="createcadastrar" id="createcadastrar" checked>
            <label for="createcadastrar">/View/{$tableSchema['name']|capitalize}/cadastrar.tpl</label>
        </div>
        <div style="margin: 15px;">
            <input type="checkbox" name="createver" id="createver" checked>
            <label for="createver">/View/{$tableSchema['name']|capitalize}/ver.tpl</label>
        </div>
        <div style="margin: 15px;">
            <input type="checkbox" name="createeditar" id="createeditar" checked>
            <label for="createeditar">/View/{$tableSchema['name']|capitalize}/editar.tpl</label>
        </div>
        <div style="margin: 15px;">
            <input type="checkbox" name="createexcluir" id="createexcluir" checked>
            <label for="createexcluir">/View/{$tableSchema['name']|capitalize}/excluir.tpl</label>
        </div>
    </div>

    {* especificações semânticas *}
    <div style="padding:30px; border:2px solid #CCC;margin:20px 10px;background:#f9f8f8">
        <h3>Especificações Gerais:</h3>
        <table>
            <tr>
                <th style="text-align: left;">Campo</td>
                <th style="text-align: left;">Label</th>
                <th style="text-align: left;">Finalidade</th>
            </tr>
            {foreach $tableSchema['fields'] as $key => $m}

                <tr>
                    {if $m->fk neq '0'}
                        <td width="20%" style="color:red">
                            <label for="label_{$m->Field}"><strong>{$m->Field} {$m->Key} (FK)</strong></label>
                            <div><small>{$m->Type}</small></div>
                        </td>
                        <td>
                            <input type="text" spellcheck="true" value="{$m->fk}" id="label_{$m->Field}"
                                name="label_{$m->Field}">
                        </td>
                        <td>
                            <select name="finalidade_{$m->Field}" id="finalidade_{$m->Field}">
                                <option value="selectFK">Select option</option>
                                <option value="radioFK">Input radio</option>
                            </select>
                            <select name="finalidadeSignificativo_{$m->Field}" id="finalidade_{$m->Field}">
                            {foreach $dbSchema[$m->fk]['fields'] as $sfk}
                                <option value="{$sfk->Field}" {if $sfk->selected neq ''}selected{/if}>exibir {$m->fk}.{$sfk->Field}</option>
                            {/foreach}
                            </select>
                        </td>
                    {else}
                        <td width="20%">
                            <label for="label_{$m->Field}"><strong>{$m->Field} {$m->Key}</strong></label>
                            <div><small>{$m->Type}</small></div>
                        </td>
                        <td>
                            <input type="text" spellcheck="true" id="label_{$m->Field}" value="{$m->Field}"
                                name="label_{$m->Field}">
                        </td>
                        <td>
                            <select name="finalidade_{$m->Field}" id="finalidade_{$m->Field}">
                                <option value="text">linha de texto geral</option>
                                <option value="html" {if $m->InputType eq 'textarea'}selected{/if}>texto longo</option>
                                <option value="number" {if $m->InputType eq 'number'}selected{/if}>número</option>
                                <option value="now" {if $m->InputType eq 'now'}selected{/if}>agora (data e hora atual)</option>
                                <option value="date" {if $m->InputType eq 'date'}selected{/if}>data</option>
                                <option value="datetime" {if $m->InputType eq 'datetime'}selected{/if}>data e hora</option>
                                <option value="time" {if $m->InputType eq 'time'}selected{/if}>hora minuto e segundo</option>
                                <option value="password">senha</option>
                                <option value="checkbox" {if $m->InputType eq 'checkbox'}selected{/if}>Checkbox (boolean)</option>
                            </select>
                        </td>
                    {/if}
                </tr>
            {foreachelse}
                <p>Nenhum campo encontrado.</p>
            {/foreach}
        </table>
    </div>

    {* campo mais significativo *}
    <div style="padding:30px; border:2px solid #CCC;margin:20px 10px;background:#f9f8f8">
        <h3>Campo mais significativo</h3>
        <p>Escolha qual campo possui a informação mais significativa para visualização. Será utilizado para títulos e
            listas.</p>
        <select name="campoSignificativo" id="campoSignificativo">
            {foreach $tableSchema['fields'] as $f}
                <option value="{$f->Field}" {if $f->selected neq ''}selected{/if}>{$f->Field}</option>
            {/foreach}
        </select>
    </div>

    {* especificações página ver *}
    <div style="padding:30px; border:2px solid #CCC;margin:20px 10px;background:#f9f8f8">
        <h3>Página ver</h3>
        <p>Defina o que será exibido na página de visualização de um registro desta tabela.</p>
        <table>
            <tr>
                <th>Exibir?</th>
                <th style="text-align: left;">Atributo</th>
                <th style="text-align: left;">Informação Vinculada</th>
            </tr>
            {foreach $tableSchema['fields'] as $key => $m}
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" name="ver_{$m->Field}" id="ver_{$m->Field}" checked>
                    </td>
                    <td>
                        <label for="ver_{$m->Field}">{$m->Field}</label>
                    </td>
                    <td>
                        {if $m->fk neq '0'}
                            <select name="verRef_{$tableSchema['name']}_{$m->Field}" id="">
                                {foreach $dbSchema[$m->fk]['fields'] as $keyfk=> $fk}
                                    <option value="{$fk->Field}" {if $fk->selected neq ''}selected{/if}>{$m->fk}.{$fk->Field}
                                    </option>
                                {/foreach}
                            </select>
                        {else}
                            {$tableSchema['name']}.{$m->Field}
                        {/if}
                    </td>
                </tr>
            {/foreach}
            {* incluir coleções Nx1*}
            {foreach $dbSchema as $key => $m}
                {foreach $m['fk'] as $fk}
                    {if $fk->reftable eq $tableSchema['name']}
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="verLista_{$key}_{$fk->fk}" id="verLista_{$key}_{$fk->fk}" checked>
                            </td>
                            <td>
                                <label for="verLista_{$key}_{$fk->fk}">
                                    Lista de {$fk->table}(s)</label>
                            </td>
                            <td>
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            {/foreach}
            {* incluir coleções NxN*}
            {foreach $dbSchema as $dbtables}
                {$ffk = ''}
                {foreach $dbtables['fk'] as $fk}
                    {if $fk->reftable eq $tableSchema['name']}
                        {$ffk = $fk->fk}
                        {continue}
                    {/if}
                    {if $ffk}
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="verListaNN_{$fk->reftable}s{$fk->used}"
                                    id="verListaNN_{$fk->reftable}s{$fk->used}" checked>
                            </td>
                            <td>
                                <label for="verListaNN_{$fk->reftable}s{$fk->used}">
                                    Lista de {$fk->reftable}(s)<br>
                                    <small>(NxN) via {$fk->table}.{$fk->fk}</small>
                                </label>
                            </td>
                            <td>
                            </td>
                        </tr>
                    {/if}
                {/foreach}
                {$ffk = ''}
                {foreach $dbtables['fk']|@array_reverse:true as $fk}
                    {if $fk->reftable eq $tableSchema['name']}
                        {$ffk = $fk->fk}
                        {continue}
                    {/if}
                    {if $ffk}
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="verListaNN_{$fk->reftable}s{$fk->used}"
                                    id="verListaNN_{$fk->reftable}s{$fk->used}" checked>
                            </td>
                            <td>
                                <label for="verListaNN_{$fk->reftable}s{$fk->used}">
                                    Lista de {$fk->reftable}(s)<br>
                                    <small>(NxN) via {$fk->table}.{$fk->fk}</small>
                                </label>
                            </td>
                            <td>
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            {/foreach}

        </table>
    </div>

    {* botão salvar *}
    <div>
        <input type="submit" value="Salvar">
    </div>
    <hr>
</form>