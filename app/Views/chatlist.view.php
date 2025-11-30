<?php 
    if(!isset($_SESSION['unique_id'])){
        header("location: login.php");
    }
?>
<?php include_once 'header.php'?>
    <div class = "wrapper">
         <section class = "users">
            <header>
                <div class="content">
                    <img src="images/<?php echo $data['img']?>" alt="">
                    <div class="details">
                        <span><?php echo $data['fname'].' '.$data['lname']?></span>
                        <p><?php ($data['status'] == 1)? $status = "Active Now": $status = "Non Active"; echo $status?></p>
                    </div>
                </div>
                <!-- <div class="logout">Logout</div> -->
                <a href="login.php" class="logout">Logout</a>
            </header>
            <div class="search">
                <span class="text">Select an user to start chat</span>
                <input type="text" name="" placeholder="Enter name to search">
                <button><i class="f-search"></i></button>
            </div>
            <div class="users-list">
                
            </div>
         </section>
    </div>
    <script src="JS/users.js"></script>
</body>
</html>