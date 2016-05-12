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

<style>
    ul{
        padding-left: 3em;
        line-height: 2.5em;
        list-style-type: dot;
    }
</style>
