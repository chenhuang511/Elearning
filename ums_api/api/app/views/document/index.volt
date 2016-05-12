<section>
    <div class="container">
        <ul class="docs-nav">
            <li><strong>Getting Started</strong></li>
            <li><a href="#welcome" class="cc-active">1. Welcome</a></li>
            <li><a href="#features" class="cc-active">2. Features</a></li>
            <li><a href="#license" class="cc-active">3. License</a></li>
            <li class="separator"></li>
            <li><strong>User Authenticate</strong></li>
            <li><a href="#userauthenticate_bysystem" class="cc-active">4. By System</a></li>
            <li><a href="#userauthenticate_byfacebook" class="cc-active">5. By Facebook</a></li>
            <li class="separator"></li>
            <li><strong>User</strong></li>
            <li><a href="#usercrud_create" class="cc-active">6. Create User</a></li>
            <li><a href="#usercrud_update" class="cc-active">7. Update user</a></li>
            <li><a href="#usercrud_detailbyid" class="cc-active">8. Detail user by userid</a></li>
            <li><strong>Role</strong></li>
            <li><a href="#role_list" class="cc-active">9. Role List</a></li>
            <li><strong>Permission</strong></li>
            <li><a href="#permission_list" class="cc-active">10. Permission List</a></li>
            <li><a href="#permission_add" class="cc-active">11. Permission Add</a></li>
        </ul>
        <div class="docs-content">
            <h2>I. Getting Started</h2>
            {% include "document/start.volt" %}

            <h2>II. API</h2>
            <p>First step: add this line into hosts file of system.</p>
            <pre class="prettyprint">
                222.252.27.89 api.ums.dev
            </pre>
            <ul>
                <li>Windows: %SYSTEMROOT%\system32\drivers\etc\hosts</li>
                <li>Linux: /etc/hosts</li>
            </ul>
            <p>API General Information</p>
            <ul>
                <li>Path: <a href="javascript:void(0)">http://api.ums.dev:4449</a></li>
                <li>Method: <b>HTTP POST or GET</b></li>
                <li>Datatype: <b>Form Data</b></li>
            </ul>
            {#Authenticate by system#}
            {% include "document/authenticate_by_system.volt" %}

            {#Authenticate by facebookid#}
            {% include "document/authenticate_by_facebookid.volt" %}

            {#CreateUser#}
            {% include "document/create_user.volt" %}

            {#Update User#}
            {% include "document/update_user.volt" %}

            {#Detail User By UD#}
            {% include "document/detail_user_by_id.volt" %}


        </div>
    </div>
</section>