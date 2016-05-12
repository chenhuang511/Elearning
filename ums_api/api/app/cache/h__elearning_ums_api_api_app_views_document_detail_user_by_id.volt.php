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