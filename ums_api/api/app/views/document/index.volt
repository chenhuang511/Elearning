<section>
    <div class="container">
        <ul class="docs-nav">
            <li><strong>Getting Started</strong></li>
            <li><a href="#welcome" class="cc-active">Lời chào</a></li>
            <li><a href="#features" class="cc-active">Thành phần</a></li>
            <li><a href="#license" class="cc-active">License</a></li>
            <li class="separator"></li>
            <li><strong>API</strong></li>
            <li><a href="#userauthenticate" class="cc-active">User Authenticate</a></li>
        </ul>
        <div class="docs-content">
            <h2> Getting Started</h2>
            <h3 id="welcome"> Welcome</h3>
            <p>Tài liệu hướng dẫn kết nối hệ thống UMS (User Management System)</p>
            <h3 id="features"> Features</h3>
            <ul>
                <li>User Authenticate</li>
                <li>User CRUD</li>
                <li>Unit Test</li>
                <li>Documentation</li>
            </ul>
            <h3 id="license"> License</h3>
            <p>Teca Pro technology 2016 by Services Team</p>
            <h3 id="userauthenticate">User Authenticate</h3>
            <h4>1. Authenticate by username and password</h4>
            <p>Path: http://api.ums.dev:4449/auth/login</p>
            <ul>
                <li>Method: <b>HTTP POST</b></li>
                <li>Datatype: <b>Form Data</b></li>
                <li>Parameter</li>
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

            <h4>2. Authenticate by facebookid</h4>
            <p>Path: http://api.ums.dev:4449/auth/loginbyfacebookid</p>
            <ul>
                <li>Method: <b>HTTP POST</b></li>
                <li>Datatype: <b>Form Data</b></li>
                <li>Parameter</li>
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

        </div>
    </div>
</section>