<div class="skills">
    {if !$hidetitle}
    <h3 class="resumeh3">
        {str tag='myskills' section='artefact.resume'}
        {if $controls}
        {contextualhelp plugintype='artefact' pluginname='resume' section='myskills'}
        {/if}
    </h3>{/if}

    <div id="skillslist{$suffix}" class="panel-items panel-items-no-margin js-masonry" data-masonry-options='{ "itemSelector": ".panel" }'>
        {foreach from=$skills item=n}
        <div class="panel panel-default">
            <h3 class="panel-heading has-link">
                {if $n->exists}
                <a id="skills_edit_{$n->artefacttype}" href="{$WWWROOT}artefact/resume/editgoalsandskills.php?id={$n->id}" title="{str tag=edit$n->artefacttype section=artefact.resume}">
                {str tag=$n->artefacttype section='artefact.resume'}
                <span class="icon icon-pencil pull-right" role="presentation" aria-hidden="true"></span>
                <span class="sr-only">{str tag=edit}</span>
                </a>
                {else}
                <a id="skills_edit_{$n->artefacttype}" href="{$WWWROOT}artefact/resume/editgoalsandskills.php?type={$n->artefacttype}" title="{str tag=edit$n->artefacttype section=artefact.resume}">
                {str tag=$n->artefacttype section='artefact.resume'}
                <span class="icon icon-pencil pull-right" role="presentation" aria-hidden="true"></span>
                <span class="sr-only">{str tag=edit}</span>
                </a>
                {/if}
            </h3>
            <div class="panel-body">
                {if $n->description != ''}
                {$n->description|clean_html|safe}
                {else}
                <p class="no-results-small">
                    {str tag=nodescription section=artefact.resume}
                </p>
                {/if}
            </div>

            {if $n->files}
            <div id="resume_{$n->id}" class="has-attachment">
                <a class="panel-footer collapsed" aria-expanded="false" href="#attach_skill_{$n->id}" data-toggle="collapse">
                    <p class="text-left">
                        <span class="icon left icon-paperclip" role="presentation" aria-hidden="true"></span>

                        <span class="text-small">{str tag=attachedfiles section=artefact.blog}</span>
                        <span class="metadata">({$n->count})</span>
                        <span class="icon icon-chevron-down collapse-indicator pull-right" role="presentation" aria-hidden="true"></span>
                    </p>

                </a>
                <div id="attach_skill_{$n->id}" class="collapse">
                    <ul class="list-unstyled list-group">
                        {foreach from=$n->files item=file}
                        <li class="list-group-item-text list-group-item-link">
                            <a href="{$WWWROOT}artefact/file/download.php?file={$file->attachment}" '{if $file->description}' title="{$file->description}" data-toggle="tooltip" '{/if}'>
                                {if $file->icon}
                                <img src="{$file->icon}" alt="" class="file-icon">
                                {else}
                                <span class="icon icon-{$file->artefacttype} icon-lg text-default" role="presentation" aria-hidden="true"></span>
                                {/if}
                                {$file->title|truncate:40}
                            </a>
                        </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
            {/if}
        </div>
        {/foreach}
    </div>
        {if $license}
        <div class="license">
            {$license|safe}
        </div>
        {/if}
</div>
