<?php include_once 'header.php';
    //   $signCookie = require_once 'php/cookies.php';

?>
<body>
    
    <div class = "wrapper">
        <!-- Form Signup -->
         <section class = "form signup">
            <header>Register</header>
            
            <form action="" enctype="multipart/form-data" method="POST">
                <div class="error-txt"></div>

                <div class="name-details">          
                    <div class="field input">
                        <!-- <label for="">First Name</label> -->
                        <input type="text" name="fname" placeholder="First Name" required>
                    </div>
                    <div class="field input">
                        <!-- <label for="">Last Name</label> -->
                        <input type="text" name="lname" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="field input">
                        <!-- <label for="">Email Adress</label> -->
                        <input type="text" name="email" placeholder="Enter your email" required>
                    </div>
                <div class="field input">
                        <!-- <label for="">Password</label> -->
                        <input type="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-eye">👁️</i>
                    </div>
                <div class="field image">
                        <label for="">Select Image</label>
                        <input type="file" name="image">
                    </div>
                <div class="field button">
                        <input type="submit" name="" value="Sign up">
                    </div>
            </form>
            <div class="link">Already signed up <a href="login.php">Log in</a></div>
         </section>
    </div>
    <script src="../../public/JS/pass-show-hide.js"></script>
    <script src="../../public/JS/signup.js"></script>
   
</body>
</html>
