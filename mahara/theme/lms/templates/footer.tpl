
                    </div><!-- end main-column -->

                    </div><!-- mainmiddle -->

                    </main>

                    {if $SIDEBARS && $SIDEBLOCKS.right}
                            <div class="col-md-3 sidebar">
                                {include file="sidebar.tpl" blocks=$SIDEBLOCKS.right}
                            </div>
                    {/if}

                    {if $SIDEBARS && $SIDEBLOCKS.left}
                            <div class="col-md-3 col-md-pull-9 sidebar">
                                    {include file="sidebar.tpl" blocks=$SIDEBLOCKS.left}
                            </div>
                    {/if}

                    </div><!-- row -->

             </div><!-- container -->

     </div><!-- middle-container-wrap -->

     <footer class="footer" id="footer">
        <div class="{if $editing == true}editcontent{/if} container">
            <div class="footer-inner">
                <ul class="nav nav-pills footer-nav">
                {foreach from=$FOOTERMENU item=item name=footermenu}
                    <li class="">
                        <a href="{$item.url}">{$item.title}</a>
                    </li>
                {/foreach}
                </ul>
                <div id="powered-by" class="mahara-logo logo-area">
                    <div class="logo-section">
                        <a href="{$WWWROOT}" class="logo">
                            <img src="{$sitelogo}" alt="{$sitename}">
                        </a>
                    </div>
                    <div class="logo-section">
                        <h2 class="logo-title">
                            Trường Đào Tạo Nghiệp Vụ <br> Bảo Hiểm Xã Hội Việt Nam
                        </h2>
                    </div>
                </div>
            </div>
        </div>
     </footer><!-- footer-wrap -->
        {if $ADDITIONALHTMLFOOTER}{$ADDITIONALHTMLFOOTER|safe}{/if}
        <script>
                var nav = document.getElementById('header');
                var height = nav.offsetHeight ;
                console.log(height);
                window.onscroll = function (e) {
                    var top = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0);
                    console.log(top);
                    if(top > height){
                        if(nav.className.indexOf('f-nav') == -1){
                            nav.className += " f-nav";
                        }
                    } else {
                        nav.className = nav.className.replace(/\b f-nav\b/,'');
                    }
                }
        </script>
</body>
</html>
