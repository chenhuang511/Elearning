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