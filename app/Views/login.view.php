<?php 
include_once 'header.php'?>
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
                        <input type="submit"  value="Log in">
                    </div>
            </form>
            <div class="link">Not yet signed in: <a href="register">Sign in now</a></div>
         </section>
    </div>
    <!-- [Linux paths] The real folder is "Js"; the old uppercase path only worked on Windows. -->
    <script src="app/Views/Js/pass-show-hide.js"></script>
    <script src="app/Views/Js/login.js"></script>
</body>
</html>