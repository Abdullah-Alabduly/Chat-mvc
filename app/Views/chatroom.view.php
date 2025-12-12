<?php 
    if(!isset($_SESSION['unique_id'])){
        header("location: login");
    }
    $user_id = preg_replace('/\D/', '', $_GET['user_id']);
?>
<?php include_once 'header.php'?>
    <div class = "wrapper">
        <!-- Form Signup -->
         <section class = "chat-area">
            <header>
                    <a href="profile" class="back-icon"> <i class ="arrow-left">←</i></a>
                    <img src="app/images/<?php echo $data['img']?>" alt="">
                    <div class="details">
                        <span><?=$data['fname']." ". $data['lname']?></span>
                        <p><?php ($data['status'] == 1)? $status = "Active Now": $status = "Non Active"; echo $status?></p>
                </div>
            </header>

            <div class="chat-box">  
                           <!-- it will be dynamic -->
            </div>

            <form action="" class="typing-area">
                <input type="text" name="msg_sender_id" value="<?=$_SESSION['unique_id']?>" hidden>
                <input type="text" name="msg_resciver_id"  value="<?=$user_id?>" hidden>
                <input type="text" name="msg" class="input-faild" placeholder="Type a message here...">
                <button class="send-icon"><img src="app/images/send.png" alt=""></button>
            </form>
         </section>
    </div>
    <script src="app/views/JS/chat.js"></script>
</body>
</html>