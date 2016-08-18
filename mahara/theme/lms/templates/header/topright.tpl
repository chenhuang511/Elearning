<div class="clearfix">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-responsive-collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <ul class="nav pull-right usermenu loggedin" role="menubar"><li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="student 253" aria-expanded="false">
                <span class="username hidden-sm">
                    <span class="icon icon-user" role="presentation" aria-hidden="true"></span>
                    {$USER|display_default_name}
                </span>
                <span class="picspan">
                </span>
                <b class="caret"></b></a><ul class="dropdown-menu pull-right top-right-nav">
                {strip}
                    {if $USERMASQUERADING && $LOGGEDIN}
                        <li class="backto-be-admin has-icon">
                            <a href="{$becomeyoulink}" title="{$becomeyouagain}">
                                <span class="icon icon-undo" role="presentation"></span>
                                <span class="nav-title">{$becomeyouagain}</span>
                            </a>
                        </li>
                    {/if}
                    {if $LOGGEDIN}
                        <li>
                            <a class="Trang cá nhân" title="Trang cá nhân" href="{profile_url($USER)}">
                                <span class="iconwrapper"><i class="icon icon-dashboard"></i></span>
                                Trang cá nhân
                            </a>
                        </li>
                        <li class="divider" role="presentation"></li>
                    {/if}
                    {if $RIGHTNAV}
                        {foreach from=$RIGHTNAV item=item}
                            <li class="{$item.path}{if $item.selected}{assign var=MAINNAVSELECTED value=$item} selected{/if}{if $item.class} {$item.class}{/if}  {if $item.iconclass}has-icon{/if}">
                                <a {if $item.linkid}id="{$item.linkid}"{/if}
                                    {if $item.accesskey}accesskey="{$item.accesskey}" {/if}
                                    {if $item.aria}
                                        {foreach $item.aria key=key item=value}aria-{$key}="{$value}" {/foreach}
                                    {/if}href="{if $item.wwwroot}{$item.wwwroot}{else}{$WWWROOT}{/if}{$item.url}">
                                    {if $item.iconclass}
                                        <span class="icon icon-{$item.iconclass}" role="presentation" aria-hidden="true"></span>
                                    {/if}

                                    {if isset($item.count)}
                                        <span class="nav-title">{$item.title}
                                            <span class="navcount{if $item.countclass} {$item.countclass}{/if}">{$item.count}</span>
                                        </span>

                                    {elseif $item.title}
                                        <span class="nav-title">{$item.title}</span>
                                    {/if}
                                </a>
                            </li>
                        {/foreach}
                        <li class="btn-logout has-icon">
                            <a href="{$WWWROOT}?logout" accesskey="l">
                                <span class="icon icon-sign-out" role="presentation" aria-hidden="true"></span>
                                <span class="nav-title">{str tag="logout"}</span>
                            </a>
                        </li>
                    {/if}
                {/strip}
                {if !$LOGGEDIN && !$SHOWLOGINBLOCK && !$LOGINPAGE}
                    <li id="loginlink" class="has-icon login-link">
                        <a href="{$WWWROOT}?login" accesskey="l">
                            <span class="icon icon-sign-in" role="presentation" aria-hidden="true"></span>
                            <span>{str tag="login"}</span>
                        </a>
                    </li>
                {/if}
                {if !$nosearch && !$LOGGEDIN && $languageform}
                    <li id="language" class="language-form">
                        {$languageform|safe}
                    </li>
                {/if}
            </ul>
        </li>
    </ul>

</div>