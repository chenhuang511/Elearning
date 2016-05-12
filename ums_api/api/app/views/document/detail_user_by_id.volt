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