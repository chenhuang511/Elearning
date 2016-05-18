<h3 id="role_list">9. Role List</h3>
<p>Path: /rolegroup/listrole</p>
<ul>
    <li>Parameter (Http Post)</li>
</ul>
<div>
<pre class="prettyprint">
{
"limit": "số bản ghi trên một trang (all nếu muốn lấy tất bản ghi)",
"page": "trang muốn lấy"
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
    "paging": {
        "limit": "2",
        "page": "1",
        "total": "3"
    },
    "data": [
        {
            "id": "3",
            "name": "Nhóm quyền test",
            "level": "3",
            "permissions": [
                "loginsystem",
                "categoryview",
                "categoryview_view",
                "categoryview_add",
                "categoryview_update",
                "categoryview_delete",
                "event",
                "event_view",
                "event_add",
                "event_update",
                "event_delete"
            ],
            "manageid": null
        },
        {
            "id": "2",
            "name": "Mod",
            "level": "2",
            "permissions": [
                ""
            ],
            "manageid": null
        }
    ]
}
</pre>
</div>