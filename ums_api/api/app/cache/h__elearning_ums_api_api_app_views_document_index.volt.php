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
            <h3 id="welcome">1. Welcome</h3>
<p>UMS (User Management System) Documentation Connector API</p>
<h3 id="features">2. Features</h3>
<ul>
    <li>API</li>
    <li>Unit Test</li>
    <li>Documentation</li>
</ul>
<h3 id="license">3. License</h3>
<p>Teca Pro technology 2016 by Services Team</p>

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
                <li>Return Data Type: <b>JSON</b> (If you want return XML format, you need add responetype=xml parameter into your request)</li>
            </ul>
            
            <h3 id="userauthenticate_bysystem">4. User Authenticate</h3>
<p>Path: /auth/login</p>
<ul>
    <li>Parameter (Http Post)</li>
</ul>
<div>
<pre class="prettyprint">
  username: "admin",
  password: "123456"
</pre>
</div>
<ul>
    <li>Return</li>
</ul>
<div>
<pre class="prettyprint">
{
"status": ​1,
"mss": "Successfully",
"data":
    {
        "id": "1",
        "firstname": "Nguyễn Hoàng",
        "lastname": "Việt",
        "username": "admin",
        "address": "Thanh Quang - An Thượng - Hoài Đức - Hà Nội",
        "email": "vietpiano@gmail.com",
        "tokenkey": "ddgfgcfjvb3nvucq1ocfn1dc35"
    }
}
</pre>
</div>

            
            <h3 id="userauthenticate_byfacebook">5. User Authenticate By Facebook</h3>
<p>Path: /auth/loginbyfacebookid</p>
<ul>
    <li>Parameter (Http Post)</li>
</ul>
<div>
<pre class="prettyprint">
  fbid: "123456"
</pre>
</div>
<ul>
    <li>Return</li>
</ul>
<div>
<pre class="prettyprint">
{
"status": ​1,
"mss": "Successfully",
"data":{
        "id": "1",
        "firstname": "Nguyễn Hoàng",
        "lastname": "Việt",
        "username": "admin",
        "address": "Thanh Quang - An Thượng - Hoài Đức - Hà Nội",
        "email": "vietpiano@gmail.com",
        "tokenkey": "ddgfgcfjvb3nvucq1ocfn1dc35"
    }
}
</pre>
</div>

            
            <h3 id="usercrud_create">6. Create User</h3>
<p>Path: /user/create</p>
<ul>
    <li>Parameter (Http Post)</li>
</ul>
<div>
<pre class="prettyprint">
{
"username": "Tên đăng nhập",
"password": "mật khẩu",
"email": "email có thể để trống",
"firstname": "Họ",
"lastname": "Tên",
"dob": "Ngày tháng năm sinh (dd-mm-yyyy)",
"address": "địa chỉ",
"phone": "số điện thoại",
"gender": "giới tính (1:Nam,2:Nữ)",
}
</pre>
</div>
<ul>
    <li>Return</li>
</ul>
<div>
<pre class="prettyprint">
{
  "status": 1,
  "mss": "Successfully",
  "data": {
    "id": "1",
    "firstname": "Nguyễn Hoàng",
    "lastname": "Việt",
    "username": "admin",
    "address": "Thanh Quang - An Thượng - Hoài Đức - Hà Nội",
    "email": "vietpiano@gmail.com",
    "tokenkey": "ddgfgcfjvb3nvucq1ocfn1dc35"
  }
}
</pre>
</div>

            
            <h3 id="usercrud_update">7. Update User</h3>
<p>Path: /user/update</p>
<ul>
    <li>Parameter (Http Post)</li>
</ul>
<div>
<pre class="prettyprint">
{
"tokenkey":"xxxx",
"password": "mật khẩu",
"email": "email có thể để trống",
"firstname": "Họ",
"lastname": "Tên",
"dob": "Ngày tháng năm sinh (dd-mm-yyyy)",
"address": "địa chỉ",
"phone": "số điện thoại",
"gender": "giới tính (1:Nam,2:Nữ)",
"Ghi chú : trường dữ liệu trống sẽ tự động được unset",
}
</pre>
</div>
<ul>
    <li>Return</li>
</ul>
<div>
<pre class="prettyprint">
{
  status: 1,
  mss: "Successfully",
  data: {
    id: "1",
    firstname: "Nguyễn Hoàng",
    lastname: "Việt",
    username: "admin",
    address: "Hà Nội",
    email: "vietpiano@gmail.com"
  }
}
</pre>
</div>

            
            <h3 id="usercrud_detailbyid">8. Detail User By ID</h3>
<p>Path: /user/detail</p>
<ul>
    <li>Parameter (Http Post)</li>
</ul>
<div>
<pre class="prettyprint">
{
"userid": "mã user của người dùng muốn xem thông tin"
}
</pre>
</div>
<ul>
    <li>Return</li>
</ul>
<div>
<pre class="prettyprint">
{
  status: 1,
  mss: "Successfully",
  data: {
    id: "5",
    firstname: "Nguyễn Thế",
    lastname: "Cơ",
    username: "supercom",
    address: "164 Khuất Duy Tiến",
    email: "nhacdjchamvn@gmail.com"
  }
}
</pre>
</div>

            
            <h3 id="role_list">9. Role List</h3>
<p>Path: /rolegroup/listrole</p>
<ul>
    <li>Parameter (Http Post)</li>
</ul>

<ul>
    <li>Return</li>
</ul>
<div>
<pre class="prettyprint">
{
    status: 1,
    mss: "Successfully",
    data: [
        {
            id: "1",
            name: "Super Administrator",
            level: "1",
            datecreate: null,
            usercreate: "1",
            permissions: "loginsystem,rolegroup,rolegroup_view,rolegroup_add,rolegroup_update,rolegroup_delete,user,user_view,user_add,user_update,user_delete,user_role,permission,permission_view,permission_add,permission_update,permission_delete",
            manageid: "all,1"
        },
        {
            id: "2",
            name: "Mod",
            level: "2",
            datecreate: null,
            usercreate: "1",
            permissions: null,
            manageid: null
        },
        {
            id: "3",
            name: "Nhóm quyền test",
            level: "3",
            datecreate: "1461122848",
            usercreate: "1",
            permissions: "loginsystem,categoryview,categoryview_view,categoryview_add,categoryview_update,categoryview_delete,event,event_view,event_add,event_update,event_delete",
            manageid: null
        }
    ]
}
</pre>
</div>


        </div>
    </div>
</section>