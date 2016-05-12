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
            <h3 id="welcome">1. Welcome</h3>
<p>UMS (User Management System) Documentation Connector API</p>
<h3 id="features">2. Features</h3>
<ul>
    <li>User Authenticate</li>
    <li>User CRUD</li>
    <li>Unit Test</li>
    <li>Documentation</li>
</ul>
<h3 id="license">3. License</h3>
<p>Teca Pro technology 2016 by Services Team</p>

            <h2>II. API</h2>
            <ul>
                <li>Path: <a href="javascript:void(0)">http://api.ums.dev:4449</a></li>
                <li>Method: <b>HTTP POST (GET)</b></li>
                <li>Datatype: <b>Form Data</b></li>
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
  <li>Note </li>
</ul>
<div>
<pre class="prettyprint">
"Đây là api cho việc update profice của chính người dùng"
"Userid cần update sẽ được lấy từ tokenkey nhận được"
"Trường dữ liệu trống sẽ tự động được unset"
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
        "username": "admin",
        "password": "6a0d8f756dfa9c1cd69fed28423ec8ca",
        "firstname": "Nguyễn Hoàng",
        "lastname": "Việt",
        "type": "1",
        "avatar": "uploads/2016/03/16//picture/f3a369bb43b7c13bcc7693250c6c40c5_desert.jpg",
        "dob": "0",
        "email": "vietpiano@gmail.com",
        "address": "Hà Nội",
        "phone": "0123456789",
        "datecreate": "1462961632",
        "usercreate": "1",
        "gender": "1",
        "private_permission": null,
        "flags": "system",
        "classid": null,
        "activekey": null,
        "fbid": "123456",
        "fbemail": "",
        "status": null,
        "active_register": null,
        "phone2": null,
        "father_name": null,
        "mother_name": null,
        "captions": null,
        "pos_id": null
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
"tokenkey":"xxxxx",
"userid": "Tên đăng nhập"
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


        </div>
    </div>
</section>