<!--nestable-->
<style>
    .tree,
    .tree ul {
        margin:0 0 0 1em; /* indentation */
        padding:0;
        list-style:none;
        color:#369;
        position:relative;
    }

    .tree ul {margin-left:.5em} /* (indentation/2) */

    .tree:before,
    .tree ul:before {
        content:"";
        display:block;
        width:0;
        position:absolute;
        top:0;
        bottom:0;
        left:0;
        border-left:1px solid;
    }

    .tree li {
        margin:0;
        padding:0 1.5em; /* indentation + .5em */
        line-height:2em; /* default list item's `line-height` */
        font-weight:bold;
        position:relative;
    }

    .tree li:before {
        content:"";
        display:block;
        width:10px; /* same with indentation */
        height:0;
        border-top:1px solid;
        margin-top:-1px; /* border top width */
        position:absolute;
        top:1em; /* (line-height/2) */
        left:0;
    }

    .tree li:last-child:before {
        background:white; /* same with body background */
        height:auto;
        top:1em; /* (line-height/2) */
        bottom:0;
    }
    a{
        font-weight:normal;
        font-size: small;
        color: red;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading head-border">
                {{ labelkey['permission.lbl_permission'] }}
            </header>
            <div class="panel-body">
                <form method="get" action="" class="form-horizontal tasi-form">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="form?parentid=0" class="btn btn-success m-b-10">{{ labelkey['general.btn_addnew'] }}</a>
                        </div>
                    </div>
                </form>
                {{ cattree }}
            </div>
        </section>
    </div>
</div>
