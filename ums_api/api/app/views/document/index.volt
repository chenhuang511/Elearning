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
            <li><strong>User CRUD</strong></li>
            <li><a href="#usercrud_create" class="cc-active">6. Create User</a></li>
            <li><a href="#usercrud_update" class="cc-active">7. Update user</a></li>
            <li><a href="#usercrud_detailbyid" class="cc-active">8. Detail user by userid</a></li>
        </ul>
        <div class="docs-content">
            <h2>I. Getting Started</h2>
            {% include "document/start.volt" %}

            <h2>II. API</h2>
            <ul>
                <li>Path: <a href="javascript:void(0)">http://api.ums.dev:4449</a></li>
                <li>Method: <b>HTTP POST (GET)</b></li>
                <li>Datatype: <b>Form Data</b></li>
            </ul>
            {#Authenticate by system#}
            {% include "document/authenticate_by_system.volt" %}

            {#Authenticate by facebookid#}
            {% include "document/authenticate_by_facebookid.volt" %}

            {#CreateUser#}
            {% include "document/create_user.volt" %}

            {#Update User#}
            {% include "document/create_user.volt" %}

            {#Detail User By UD#}
            {% include "document/detail_user_by_id.volt" %}


        </div>
    </div>
</section>