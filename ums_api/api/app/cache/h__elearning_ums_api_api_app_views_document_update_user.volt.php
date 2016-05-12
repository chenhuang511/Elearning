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