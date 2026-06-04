<?php
    include 'session.php';
    include 'connect.php';
    $currentInitial = strtoupper($_SESSION['username'][0]);
    $current_username=$_SESSION['username'];
    $receiver_id = $_SESSION['receiver_id'] ?? "";
    $groups_id = $_SESSION['groups_id'] ?? "";
    $selected_user="";
    $selected_avatar="";
    if($receiver_id!="")
    {
        $sql="select username from users where id='$receiver_id'";
        $res=mysqli_query($conn,$sql);
        if(mysqli_num_rows($res)>0)
            {
                $row=mysqli_fetch_assoc($res);
                $selected_user=$row['username'];
                $selected_avatar=strtoupper($selected_user[0]);
            }
    }
    if($groups_id!="")
    {
        $sql="select group_name from groups where id='$groups_id'";
        $res=mysqli_query($conn,$sql);
        if(mysqli_num_rows($res)>0)
            {
                $row=mysqli_fetch_assoc($res);
                $selected_user=$row['group_name'];
                $selected_avatar=strtoupper($selected_user[0]);
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEPL Chat</title>
    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --bg: #f3f6ef;
            --panel: rgba(255, 255, 255, 0.92);
            --panel-soft: rgba(255, 255, 255, 0.78);
            --text: #15231c;
            --muted: #68766d;
            --line: rgba(21, 35, 28, 0.08);
            --green: #6b8f5a;
            --green-dark: #4f7a68;
            --green-soft: rgba(107, 143, 90, 0.13);
            --shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(107, 143, 90, 0.18), transparent 28%),
                radial-gradient(circle at bottom right, rgba(79, 122, 104, 0.14), transparent 25%),
                linear-gradient(135deg, #f7faf5, var(--bg));
        }

        .app {
            min-height: 100vh;
            height: 100vh;
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
        }

        .header,
        .chat-window,
        .composer {
            background: var(--panel);
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
        }

        .avatar {
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 700;
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            width: 46px;
            height: 46px;
            border-radius: 16px;
            font-size: 0.95rem;
            flex: 0 0 auto;
        }

        .main {
            padding: 18px;
            display: grid;
            gap: 14px;
            min-width: 0;
            min-height: 0;
            height: 100vh;
            grid-template-rows: auto 1fr auto;
        }

        .header,
        .composer,
        .chat-window {
            border-radius: 22px;
        }

        .header {
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            width: 100%;
        }

        .room-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-left: auto;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.85);
            color: var(--text);
            text-decoration: none;
            font-size: 0.86rem;
        }

        .chip.primary {
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            color: white;
            border-color: transparent;
        }

        .user-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
        }

        .user-chip .avatar {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            font-size: 0.86rem;
        }

        .user-chip strong {
            font-size: 0.92rem;
        }

        .chat-window {
            padding: 18px;
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }

        .messages {
            display: grid;
            gap: 12px;
            align-content: start;
            min-height: 0;
            height: 100%;
            overflow-y: auto;
            padding: 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.58);
            border: 1px dashed rgba(107, 143, 90, 0.14);
        }

        .empty-chat {
            margin: auto;
            max-width: 360px;
            text-align: center;
            color: var(--muted);
            line-height: 1.55;
        }

        .empty-chat strong {
            display: block;
            color: var(--text);
            font-size: 1rem;
            margin-bottom: 6px;
        }

        .message-row {
            display: flex;
            align-items: end;
            gap: 10px;
            width: 100%;
        }

        .message-row.mine {
            justify-content: flex-end;
        }

        .bubble {
            max-width: 100%;
            padding: 12px 14px;
            border-radius: 18px 18px 18px 6px;
            background: #f4f7f1;
            border: 1px solid rgba(107, 143, 90, 0.08);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }

        .message-row.mine .bubble {
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            color: #fff;
            border-radius: 18px 18px 6px 18px;
        }

        .bubble p {
            margin: 0;
            line-height: 1.5;
            font-size: 0.94rem;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .time {
            font-size: 0.72rem;
            opacity: 0.9;
            letter-spacing: 0.01em;
        }

        .message-row.mine .meta {
            color: rgba(255, 255, 255, 0.85);
            justify-content: flex-end;
        }

        .label {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 999px;
            background: var(--green-soft);
            color: var(--green-dark);
            font-weight: 700;
        }

        .message-row.mine .label {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        .composer {
            padding: 14px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 10px;
            align-items: center;
        }

        .composer .avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            font-size: 0.9rem;
        }

        .composer input {
            width: 100%;
            border: 1px solid #d6ded4;
            border-radius: 14px;
            padding: 12px 14px;
            background: #f9fbf8;
            color: var(--text);
            font: inherit;
        }

        .composer input:focus {
            outline: none;
            border-color: rgba(107, 143, 90, 0.55);
            box-shadow: 0 0 0 4px rgba(107, 143, 90, 0.12);
        }

        .send {
            border: 0;
            border-radius: 14px;
            padding: 12px 16px;
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
        .bubble-wrapper{
    position:relative;
    display:inline-block;
    width:auto;
    max-width:min(520px, 84%);
}

.message-row.mine .bubble-wrapper{
    margin-left:auto;
}

.msg-menu{
    position:absolute;
    top:6px;
    right:8px;
    opacity:0;
    transition:0.2s;
}

.message-row:hover .msg-menu{
    opacity:1;
}

.menu-btn{
    border:none;
    background:rgba(255,255,255,0.9);
    width:22px;
    height:22px;
    border-radius:50%;
    cursor:pointer;
    font-size:11px;
    color:#667781;
    display:flex;
    align-items:center;
    justify-content:center;
}

.message-row.mine .menu-btn{
    background:rgba(255,255,255,0.85);
}

.menu-dropdown{
    display:none;
    position:absolute;
    right:0;
    top:28px;
    background:#fff;
    border-radius:8px;
    min-width:140px;
    box-shadow:0 2px 10px rgba(0,0,0,0.15);
    overflow:hidden;
    z-index:100;
}

.menu-dropdown button{
    border:none;
    background:none;
    width:100%;
    padding:10px 14px;
    text-align:left;
    cursor:pointer;
    font-size:14px;
}

.menu-dropdown button:hover{
    background:#f5f6f6;
}

.msg-menu.active .menu-dropdown{
    display:block;
}
.hidden{
    display:none !important;
}
.unread-badge{
    position: absolute;
    top: 10px;
    right: 12px;
    
    background: linear-gradient(135deg, var(--green), var(--green-dark));
    color: #fff;
    min-width: 20px;
    height: 20px;
    padding: 0 7px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    box-shadow: 0 4px 10px rgba(229,83,83,0.28);
    border: 2px solid #fff;
    transform-origin: center;
    transition: transform 120ms ease, opacity 120ms ease;
    white-space: nowrap;
}
.unread-badge:empty{ display:none; }
.unread-badge[data-count="99+"]{ padding-left:6px; padding-right:6px; }
.chat-info{
    display:flex;
    align-items:center;
    gap:12px;
}

.chat-info strong{
    font-size:18px;
    color:var(--text);
}
.date-divider{
    text-align:center;
    margin:12px 0;
}

.date-divider span{
    display:inline-block;
    padding:6px 12px;
    background:#fff;
    border-radius:8px;
    font-size:13px;
    color:#667781;
    box-shadow:0 1px 2px rgba(0,0,0,0.1);
}
#conversationHeader{
    padding:12px 18px;
    margin-bottom:8px;
    border-radius:12px;
    position:relative;
    display:flex;
    align-items:center;
    gap:12px;
    background:rgba(255,255,255,0.6);border:1px solid var(--line)
}
#groupModal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15, 23, 42, 0.58);
    backdrop-filter: blur(8px);
    display:flex;
    justify-content:center;
    align-items:center;
    z-index:1000;
    padding:16px;
}
.modal-box{
    width:min(100%, 420px);
    background:rgba(255,255,255,0.97);
    border:1px solid rgba(107, 143, 90, 0.16);
    border-radius:24px;
    box-shadow:0 28px 70px rgba(15, 23, 42, 0.24);
    padding:18px;
    display:grid;
    gap:10px;
}
#memberStep,
#nameStep{
    padding:12px;
    border-radius:18px;
    background:rgba(243, 246, 239, 0.76);
    border:1px solid rgba(107, 143, 90, 0.12);
}
#memberStep h3,
#nameStep h3{
    margin:0 0 10px;
    color:var(--text);
    font-size:1rem;
    letter-spacing:0.01em;
}
#memberList{
    max-height:300px;
    overflow-y:auto;
    padding-right:4px;
}
#groupModal input[type="text"]{
    width:100%;
    min-height:42px;
    border-radius:14px;
    border:1px solid rgba(107, 143, 90, 0.18);
    padding:0 14px;
    font-size:0.95rem;
    outline:none;
    background:#fff;
}
#groupModal input[type="text"]:focus{
    border-color:rgba(107, 143, 90, 0.42);
    box-shadow:0 0 0 4px rgba(107, 143, 90, 0.12);
}
#groupModal button{
    border:none;
    border-radius:999px;
    min-height:40px;
    padding:0 16px;
    font-weight:600;
    cursor:pointer;
    transition:transform .18s ease, box-shadow .18s ease, background .18s ease;
}
#groupModal button:hover{
    transform:translateY(-1px);
}
#nextStep,
#createGroup{
    background:linear-gradient(135deg, var(--green), var(--green-dark));
    color:#fff;
    box-shadow:0 10px 22px rgba(79, 122, 104, 0.24);
}
#nextStep{
    min-width:96px;
}
#createGroup{
    min-width:120px;
}
#nextStep,
#createGroup{
    margin-top:12px;
}
#closeConversation{
    width:34px;
    height:34px;
    position:absolute;
    right:18px;
    top:50%;
    transform:translateY(-50%);
    border:none;
    border-radius:50%;
    display:grid;
    place-items:center;
    background:rgba(107, 143, 90, 0.10);
    color:var(--green-dark);
    cursor:pointer;
    font-size:16px;
    padding:0;
    line-height:1;
    transition:background .18s ease, color .18s ease, box-shadow .18s ease;
}
#closeConversation:hover{
    background:rgba(107, 143, 90, 0.18);
    box-shadow:0 8px 16px rgba(79, 122, 104, 0.16);
}
#closeConversation:focus-visible,
#groupModal button:focus-visible,
#groupModal input[type="text"]:focus-visible{
    outline:3px solid rgba(107, 143, 90, 0.22);
    outline-offset:2px;
}
        @media (max-width: 980px) {
            .app {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .main,
            .sidebar {
                padding: 12px;
            }

            .header {
                display: flex;
                justify-content: flex-end;
            }

            .room-actions {
                width: auto;
            }

            .chip {
                flex: 0 0 auto;
            }

            .composer {
                grid-template-columns: auto 1fr;
            }

            .send {
                grid-column: 1 / -1;
                width: 100%;
            }
        }
    </style>
</head>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let menuOpen=false;
function loadMessages()
{
    if(menuOpen)
    {
        return;
    }
    let messages=$("#messages");
    let wasNearBottom=
    (
        messages[0].scrollHeight
        -
        messages.scrollTop()
        -
        messages.innerHeight()
    )<80;
    $.ajax({
        url:"load_message.php",
        type:"POST",
        success:function(data)
        {
            messages.html(data);

            if(
                wasNearBottom
                ||
                messages.data("loaded")!="true"
            )
            {
                messages.scrollTop(
                    messages[0].scrollHeight
                );
            }
            messages.data("loaded","true");
        },
    });
}

$(document).ready(function()
{
    <?php
    if($receiver_id!="" || $groups_id!="")
    {
        ?>
        $('#conversationHeader').removeClass("hidden");
        $("#messageForm").removeClass("hidden");
        <?php
    }
    ?>
    loadMessages();
    setInterval(loadMessages,1000);
    $(document).on(
        "click",
        ".private-chat",
        function()
        {
            let receiver_id=$(this).data("id");
            let receiver_name=$(this).data("name");
            $("#conversationHeader").removeClass("hidden");
            $("#messageForm").removeClass("hidden");
            $("#convAvatar").text(receiver_name.charAt(0).toUpperCase());
            $("#convTitle").text(receiver_name);
            $(".private-chat").removeClass("active");
            $(this).addClass("active");
            $.ajax({
                url:"set_receiver.php",
                type:"POST",
                data:{
                    receiver_id:receiver_id
                },
                success:function()
                {
                    loadMessages();
                    $.ajax({
                        url:"get_users_chat.php",
                        type:"POST",
                        success:function(data)
                        {
                            $("#chat-list").html(data);
                        }
                    });
                }
            });
        }
    );
    $(document).on(
        "click",
        ".group-chat",
        function()
        {
            let groups_id=$(this).data("group-id");
            let group_name=$(this).data("name");
            $("#conversationHeader").removeClass("hidden");
             $("#messageForm").removeClass("hidden");
            $("#convAvatar").text(group_name.charAt(0).toUpperCase());
            $("#convTitle").text(group_name);
            $(".group-chat").removeClass("active");
            $(this).addClass("active");
            $.ajax({
                url:"set_group.php",
                type:"POST",
                data:{
                    groups_id:groups_id
                },
                success:function()
                {
                    loadMessages();
                    $.ajax({
                        url:"get_users_chat.php",
                        type:"POST",
                        success:function(data)
                        {
                            $("#chat-list").html(data);
                        }
                    });
                }
            });
        }
    );
    $(document).on(
        "submit","#messageForm",
        function(e)
        {
            e.preventDefault();
            let message=$("#message").val();
            if(message.trim()=="")
            {
                return;
            }
            $.ajax({
                url:"send_message.php",
                type:"POST",
                data:{
                    message:message
                },
                success:function()
                {
                    $("#message").val("");
                    loadMessages();
                    $.ajax({
                        url:"get_users_chat.php",
                        type:"POST",
                        success:function(data)
                        {
                            $("#chat-list").html(data);
                        }
                    });
                }
            });
        }
    );
});
$(document).on('click',".menu-btn",function(e)
{
    e.stopPropagation();
    $(".msg-menu").removeClass("active");
    $(this).parent().addClass("active");
    menuOpen=true;
});
$(document).click(function()
{
    $(".msg-menu").removeClass("active");
    menuOpen=false;
});
$(document).on("click",".delete-msg",function()
{
    let id=$(this).data("id");
    $.ajax({
        url:"delete_message.php",
        type:"POST",
        data:{
            id:id
        },
        success:function()
        {
            menuOpen=false;
            loadMessages();
            $.ajax({
                        url:"get_users_chat.php",
                        type:"POST",
                        success:function(data)
                        {
                            $("#chat-list").html(data);
                        }
                    });
        }
    });
});
$(document).on("click","#createGroupBtn",function()
{
    $("#groupModal").removeClass("hidden");
    $("#memberStep").removeClass("hidden");
    $("#nameStep").addClass("hidden");
    $.ajax({
        url:"select_members_forGroup.php",
        success:function(data)
        {
            $("#memberList").html(data);
        }
    });
});
let selectedMembers=[];
$(document).on("click","#nextStep",function()
{
    selectedMembers=[];
    $(".member:checked").each(function()
    {
        selectedMembers.push($(this).val());
    });
    if(selectedMembers.length==0)
    {
        alert("Please select at least one member.");
        return;
    }
    $("#memberStep").addClass("hidden");
    $("#nameStep").removeClass("hidden");
});
$(document).on("click","#createGroup",function()
{
    let groupName=$("#groupName").val().trim();
    if(groupName=="")
    {
        alert("Please enter a group name.");
        return;
    }
    $.ajax({
        url:"create_group.php",
        type:"POST",
        data:{
            name:groupName,
            members:selectedMembers
        },
        success:function()
        {
            $("#groupModal").addClass("hidden");
            $("#groupName").val("");
            $.ajax({
                url:"get_users_chat.php",
                type:"POST",
                success:function(data)
                {
                    $("#chat-list").html(data);
                }
            });
        }
    });
})
$(document).on("click","#closeConversation",function()
{
    $.ajax({
        url:"clear_chat_selection.php",
        type:"POST",
        success:function()
        {
            $("#conversationHeader").addClass("hidden");
            $("#messageForm").addClass("hidden");
            $(".private-chat, .group-chat").removeClass("active");
            loadMessages();
            $.ajax({
                url:"get_users_chat.php",
                type:"POST",
                success:function(data)
                {
                    $("#chat-list").html(data);
                }
            });
        }
    });
});
$(document).on("click","#groupModal",function(e)
{
    if($(e.target).is("#groupModal"))
    {
        $("#groupModal").addClass("hidden");
    }
});
</script>
<body>
    <div class="app">
        <?php include 'sidebar.php'; ?>
        <main class="main">
            <header class="header">
                <div class="room-actions">
                    <div class="chip user-chip">
                        <div class="avatar"><?php echo $currentInitial; ?></div>
                        <strong><?php echo $current_username; ?></strong>
                    </div>
                    <a class="chip primary" href="logout.php">Logout</a>
                </div>
            </header>
            <section class="chat-window">
                <div class="chat-info hidden" id="conversationHeader">
                    <div class="avatar" id="convAvatar">
                        <?php if ($selected_avatar!="") { echo $selected_avatar; } ?>
                    </div>

                    <div style="display:flex;align-items:center;gap:10px;">
                        <strong id="convTitle"><?php if ($selected_user!="") { echo $selected_user; } ?></strong>
                        <button type="button" id="closeConversation">
                            ✕
                        </button>
                    </div>

                </div>
                <div class="messages" id="messages">
                </div>
            </section>

            <form class="composer hidden" id="messageForm">
                <div class="avatar"><?php echo $currentInitial; ?></div>
                <input type="text" placeholder="Type a message" id="message" name="message" autocomplete="off">
                <button type="submit" class="send">Send</button>
            </form>
        </main>
    </div>
    <div id="groupModal" class="hidden">
    <div class="modal-box">
        <div id="memberStep">
            <h3>Select Members</h3>
            <div id="memberList"></div>
            <button id="nextStep">
                ✓
            </button>
        </div>
        <div id="nameStep" class="hidden">
            <h3>Group Name</h3>
            <input
                type="text"
                id="groupName"
                placeholder="Enter Group Name">
            <button id="createGroup">
                Create
            </button>
        </div>
    </div>
</div>
</body>
</html>