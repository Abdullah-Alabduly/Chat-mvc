<?php 
    // session_start();
    // if(!isset($_SESSION['unique_id'])){
    //     header("location: login.php");
    // }else{
    //    include_once 'php/userData.php';
    //    $user_id = preg_replace('/\D/', '', $_GET['user_id']);
    //    $row = getUserDate( (int) $user_id);
    // }
?>
<?php include_once 'header.php'?>
    <div class = "wrapper">
        <!-- Form Signup -->
         <section class = "chat-area">
            <header>
                    <a href="users.php" class="back-icon"> <i class ="arrow-left">←</i></a>
                    <img src="images/<?php echo $row['img']?>" alt="">
                    <div class="details">
                        <span><?=$row['fname']." ". $row['lname']?></span>
                        <p><?php ($row['status'] == 1)? $status = "Active Now": $status = "Non Active"; echo $status?></p>
                </div>
            </header>

            <div class="chat-box">  
                           <!-- it will be dynamic -->
            </div>

            <form action="" class="typing-area">
                <input type="text" name="sender_id" value="<?=$_SESSION['unique_id']?>" hidden>
                <input type="text" name="resciver_id"  value="<?=$user_id?>" hidden>
                <input type="text" name="message" class="input-faild" placeholder="Type a message here...">
                <button class="send-icon"><img src="send.png" alt=""></button>
            </form>
         </section>
    </div>
    <script src="app/views/JS/chat.js"></script>
</body>
</html>