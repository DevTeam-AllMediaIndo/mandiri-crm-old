<html>
<title>Firebase Messaging Demo</title>
<style>
    div {
        margin-bottom: 15px;
    }
</style>
<body>
    <div id="token"></div>
    <div id="msg"></div>
    <div id="notis"></div>
    <div id="err"></div>
    <script src="https://www.gstatic.com/firebasejs/7.16.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.16.1/firebase-messaging.js"></script>
    <script>
        MsgElem = document.getElementById("msg");
        TokenElem = document.getElementById("token");
        NotisElem = document.getElementById("notis");
        ErrElem = document.getElementById("err");
        // Initialize Firebase
        // TODO: Replace with your project's customized code snippet
        var config = {
            'messagingSenderId': '528913112820',
            'apiKey': 'AIzaSyADam2Zi8dOwVFZrnZ6yVUBpefzQD7hS5o',
            'projectId': 'allmediaindo-notif"',
            'appId': '1:528913112820:web:2fffdb6576e28026d448a1',
        };
        firebase.initializeApp(config);

        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                MsgElem.innerHTML = "Notification permission granted."
                console.log("Notification permission granted.");

                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
                TokenElem.innerHTML = "token is : " + token
            })
            .catch(function (err) {
                ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
                console.log("Unable to get permission to notify.", err);
            });

    </script>

    </body>

</html>