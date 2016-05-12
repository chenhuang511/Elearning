<h3 id="usercrud_detailbyid">8. Detail User By ID</h3>
<p>Path: /user/detail</p>
<ul>
    <li>Parameter (Http Post)</li>
</ul>
<div>
<pre class="prettyprint">
{
"tokenkey": "xxxx",
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