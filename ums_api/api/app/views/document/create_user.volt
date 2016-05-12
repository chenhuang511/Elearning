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