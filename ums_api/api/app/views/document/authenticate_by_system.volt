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