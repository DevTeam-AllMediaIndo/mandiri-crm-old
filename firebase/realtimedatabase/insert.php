<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
  	<body>
		<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
		<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-database.js"></script>
		<script>
			const firebaseConfig = {
				apiKey: "AIzaSyBPzDbf1xjp-JARqJNARdCJS3583PXNffk",
				authDomain: "ibftrader-6f87a.firebaseapp.com",
				databaseURL: "https://ibftrader-6f87a-default-rtdb.asia-southeast1.firebasedatabase.app",
				projectId: "ibftrader-6f87a",
				storageBucket: "ibftrader-6f87a.appspot.com",
				messagingSenderId: "814807480492",
				appId: "1:814807480492:web:b6e3d62764808cdb8d4709",
				measurementId: "G-54VJECZC7F"
			};
			firebase.initializeApp(firebaseConfig);
			const db = firebase.database();
			
			// firebase.database().ref('data').on('value',(snap)=>{
			// 	console.log(snap.val());
			// });
			db.ref().set({
				notif:Date.now() 
			})
		</script>   
  	</body>
</html>