<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .sidebar {
        grid-area: sidebar;
        padding: 0 18px 84px 18px;
        border-right: 1px solid rgba(21, 35, 28, 0.08);
        position: relative;
        min-height: 0;
        overflow-y: auto;
    }

    .sidebar #createGroupBtn {
        position: absolute;
        right: 18px;
        bottom: 18px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #6b8f5a, #4f7a68);
        color: #fff;
        font-size: 1.6rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.15);
        z-index: 2;
    }

    .sidebar .chat-list {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(21, 35, 28, 0.08);
        border-radius: 22px;
        padding: 10px;
        display: grid;
        gap: 8px;
    }

    .sidebar .search {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.76);
        border: 1px solid rgba(21, 35, 28, 0.08);
    }

    .sidebar .search-icon {
        font-size: 0.95rem;
        flex: 0 0 auto;
        color: #87948c;
    }

    .sidebar .search input {
        width: 100%;
        border: 0;
        background: transparent;
        color: #15231c;
        font: inherit;
        outline: none;
    }

    .sidebar .search input::placeholder {
        color: #87948c;
    }

    .sidebar .empty-list {
        padding: 16px 12px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.76);
        border: 1px dashed rgba(107, 143, 90, 0.18);
        color: #68766d;
        font-size: 0.9rem;
        line-height: 1.45;
    }
    .sidebar .chat-user {
    width: 100%;
    cursor: pointer;
    border: none;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    padding-right: 46px;
    border-radius: 16px;
    text-decoration: none;
    color: #15231c;
    background: rgba(255,255,255,0.76);
    border: 1px solid rgba(21,35,28,0.08);
    transition: 0.2s;
    position: relative;
}
.sidebar .chat-list form {
    margin: 0;
    padding: 0;
}

.sidebar .chat-user {
    margin: 0;
}

.sidebar .chat-user:hover {
    background: rgba(107,143,90,0.12);
}
.sidebar .chat-user.active {
    background: linear-gradient(135deg, rgba(107,143,90,0.12), rgba(79,122,104,0.08));
    border-color: rgba(107,143,90,0.18);
    box-shadow: 0 6px 18px rgba(15,23,42,0.04);
}

.sidebar .user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    color: #fff;
    font-weight: 700;
    background: linear-gradient(135deg,#6b8f5a,#4f7a68);
    flex: 0 0 auto;
}

.sidebar .user-info {
    display: grid;
}

.sidebar .user-info strong {
    font-size: 0.94rem;
}

.sidebar .last-message {
    display: block;
    margin-top: 2px;
    color: #68766d;
    font-size: 0.78rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

    @media (max-width: 980px) {
        .sidebar {
            border-right: 0;
            border-bottom: 1px solid rgba(21, 35, 28, 0.08);
        }
    }

    @media (max-width: 640px) {
        .sidebar {
            padding: 12px;
        }
    }

</style>
<aside class="sidebar">
    <div id="createGroupBtn">
        +
    </div>
    <form class="search" method="POST" onsubmit="return false;">
        <i class="fa-solid fa-magnifying-glass search-icon" aria-hidden="true"></i>
        <input type="text" name="q" placeholder="Search chats" aria-label="Search chats">
    </form>
    <div class="chat-list" id="chat-list">
        <?php include 'get_users_chat.php'; ?>
    </div>
</aside>
<script>
let searchInput=$(".search input");
let chatList=$("#chat-list");
let typingTimer;
searchInput.on(
    "keyup",
    function()
    {
        clearTimeout(typingTimer);
        let query=$(this).val().trim();
        typingTimer=setTimeout(function()
        {
            if(query==="")
            {
                $.ajax({
                    url:"get_users_chat.php",
                    type:"POST",
                    success:function(data)
                    {
                        chatList.html(data);
                    }
                });
            }
            else
            {
                $.ajax({
                    url:"search_users.php",
                    type:"POST",
                    data:{
                        query:query
                    },
                    success:function(data)
                    {
                        chatList.html(data);
                    }
                });
            }
        },300);
    }
);
</script>