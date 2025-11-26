<?php include_once 'header.php'?>
<body>

    <div class = "wrapper">
        <!-- Form Signup -->
         <section class = "form login">
            <header>Log in</header>
            
            <form action="">
                <div class="error-txt"></div>
                <div class="field input">
                        <!-- <label for="">Enter your email</label> -->
                        <input type="text" name="email" placeholder="Enter your email">
                    </div>
                <div class="field input">
                        <!-- <label for="">Enter your password</label> -->
                        <input type="password" name="password" placeholder="Enter your password">
                        <i class="fas fa-eye"></i>
                    </div>
                <div class="field button">
                        <input type="submit"  value="Sign up">
                    </div>
            </form>
            <div class="link">Not yet signed up <a href="index.php">Sign up now</a></div>
         </section>
    </div>
    <script src="JS/pass-show-hide.js"></script>
    <script src="JS/login.js"></script>
</body>
</html>