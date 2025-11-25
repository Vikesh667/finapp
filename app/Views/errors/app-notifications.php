 <?php echo view('header');?>

<body class="bg-white">

    <!-- loader -->
    <!-- <div id="loader">
        <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
    </div> -->
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="#" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            Notifications
        </div>
        <div class="right">
            <a href="#" class="headerButton" onclick="toastbox('toast-example-1', 3000)">
                <ion-icon name="notifications-off-outline"></ion-icon>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    <!-- toast bottom iconed -->
    <div id="toast-example-1" class="toast-box toast-bottom bg-primary">
        <div class="in">
            <ion-icon name="notifications-off-outline"></ion-icon>
            <div class="text">
                Notification sounds have been muted
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-text-light close-button">OK</button>
    </div>
    <!-- * toast bottom iconed -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="section full">

            <ul class="listview image-listview flush">
                <li class="active">
                    <a href="app-notification-detail.html" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="arrow-down-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>Payment Received</strong></div>
                                <div class="text-small mb-05">John sent you <b>$ 50</b></div>
                                <div class="text-xsmall">5/3/2020 10:30 AM</div>
                            </div>
                            <span class="badge badge-primary badge-empty"></span>
                        </div>
                    </a>
                </li>
                <li class="active">
                    <a href="app-notification-detail.html" class="item">
                        <div class="icon-box bg-success">
                            <ion-icon name="arrow-forward-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>Money has been sent</strong></div>
                                <div class="text-small mb-05">The money you sent to John was successfully sent.</div>
                                <div class="text-xsmall">5/3/2020 10:30 AM</div>
                            </div>
                            <span class="badge badge-primary">3</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="app-notification-detail.html" class="item">
                        <div class="icon-box bg-danger">
                            <ion-icon name="key-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>Password changed</strong></div>
                                <div class="text-small mb-05">Your password has been changed</div>
                                <div class="text-xsmall">5/3/2020 10:30 AM</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="app-notification-detail.html" class="item">
                        <div class="icon-box bg-warning">
                            <ion-icon name="chatbubble-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>New Messages</strong></div>
                                <div class="text-small mb-05">You have new messages from John</div>
                                <div class="text-xsmall">5/3/2020 10:30 AM</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="app-notification-detail.html" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="arrow-down-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>Payment Received</strong></div>
                                <div class="text-small mb-05">John sent you <b>$ 50</b></div>
                                <div class="text-xsmall">5/3/2020 10:30 AM</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="app-notification-detail.html" class="item">
                        <div class="icon-box bg-success">
                            <ion-icon name="arrow-forward-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>Money has been sent</strong></div>
                                <div class="text-small mb-05">The money you sent to John was successfully sent.</div>
                                <div class="text-xsmall">5/3/2020 10:30 AM</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="app-notification-detail.html" class="item">
                        <div class="icon-box bg-danger">
                            <ion-icon name="key-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>Password changed</strong></div>
                                <div class="text-small mb-05">Your password has been changed</div>
                                <div class="text-xsmall">5/3/2020 10:30 AM</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="app-notification-detail.html" class="item">
                        <div class="icon-box bg-warning">
                            <ion-icon name="chatbubble-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>New Messages</strong></div>
                                <div class="text-small mb-05">You have new messages from John</div>
                                <div class="text-xsmall">5/3/2020 10:30 AM</div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>

        </div>

    </div>
    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    <?php echo view('bottomMenu');?>
    <!-- * App Bottom Menu -->


    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="../../cdn.jsdelivr.net/npm/ionicons%40latest/dist/ionicons/ionicons.esm.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>


</body>

</html>