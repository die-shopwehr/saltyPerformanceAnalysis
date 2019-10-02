{extends file="parent:backend/_base/salty_performance_analysis.tpl"}

{block name="content/main"}

    {foreach key=name from=$data item=group}
        <div class="container-fluid m-0 mt-2 bg-white shadow-sm">
            <div class="row border-bottom  mb-0 bg-white p-2 pl-3 pr-3">
                <div class="col text-left pl-0">
                    <strong>{include file="string:{$name|snippet:$name:"backend/salty_performance_analysis"}"}</strong>
                </div>
                <div class="col-2 text-center">
                    <strong>{s namespace="backend/salty_performance_analysis" name="Recommended"}{/s}</strong>
                </div>
                <div class="col-2 text-center">
                    <strong>{s namespace="backend/salty_performance_analysis" name="Status"}{/s}</strong>
                </div>
                <div class="col-2 text-center">
                    <strong>{s namespace="backend/salty_performance_analysis" name="Information"}{/s}</strong>
                </div>
            </div>

            {foreach key=value from=$group item=element}
                <div class="row border-bottom mb-0 bg-light p-1 pl-3 pr-3">
                    <div class="col-1 align-self-center">
                        {if $element.status == 2}
                            <span class="badge badge-success">{s namespace="backend/salty_performance_analysis" name="Success"}{/s}</span>
                        {elseif $element.status == 1}
                            <span class="badge badge-warning">{s namespace="backend/salty_performance_analysis" name="Warning"}{/s}</span>
                        {elseif $element.status == 3}
                            <span class="badge badge-info">{s namespace="backend/salty_performance_analysis" name="Info"}{/s}</span>
                        {else}
                            <span class="badge badge-danger">{s namespace="backend/salty_performance_analysis" name="Error"}{/s}</span>
                        {/if}
                    </div>
                    <div class="col align-self-center">
                        {include file="string:{$value|snippet:$value:"backend/salty_performance_analysis"}"}
                    </div>
                    <div class="col-2 text-center align-self-center">
                        {if $element.recommendation === false}
                            {s namespace="backend/salty_performance_analysis" name="inactive"}{/s}
                        {elseif $element.recommendation === true}
                            {s namespace="backend/salty_performance_analysis" name="active"}{/s}
                        {else}
                            {$element.recommendation}
                        {/if}
                    </div>
                    <div class="col-2 text-center align-self-center">
                        {if $element.value === false}
                            {s namespace="backend/salty_performance_analysis" name="inactive"}{/s}
                        {elseif $element.value === true}
                            {s namespace="backend/salty_performance_analysis" name="active"}{/s}
                        {else}
                            {$element.value}
                        {/if}
                    </div>
                    <div class="col-2 text-center align-self-center">
                        {assign var="url" value="$value"|cat:"_url"}

                        <a class="btn btn-primary btn-sm" href="{include file="string:{$url|snippet:$url:"backend/salty_performance_analysis"}"}" target="_blank" role="button">Link</a>
                    </div>
                </div>
            {/foreach}

        </div>
    {/foreach}
{/block}