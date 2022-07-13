<!DOCTYPE html>
<link rel='manifest' href='web.manifest'>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.5/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.5/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.5/firebase-auth.js"></script>
<script type="text/javascript">
var loggedIn;
var firebaseConfig = {

};
var GoogleAuth; // Google Auth object.
firebase.initializeApp(firebaseConfig);
var db = firebase.firestore();

function initClient(credi){
  // console.log(credi);
  // GoogleAuth = credi;
  provider = new firebase.auth.GoogleAuthProvider();
  // provider.addScope('https://www.googleapis.com/auth/calendar');
  // provider.credential();
  firebase.auth().signInWithPopup(provider).then(resp=>{
    gapi.load('client:auth2', test);
  });
  function test(init) {
      gapi.client.init(gcreds);
      console.log(init);
  }

}
var gcreds = {
  'apiKey': 'AIzaSyDR53wNnENbFeOymPE-ghDkgG93F2dwQpA',
  'clientId': '1043966159415-0kspdv02a2tdfjtj6r5sj7gcgs3t15vk.apps.googleusercontent.com',
  'scope': 'https://www.googleapis.com/auth/calendar',
  'discoveryDocs': ['https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest']
};
// function logIn() {
//   gapi.client.init(gcreds);
//   gapi.auth2.init(gcreds).then(r=>{
//     GoogleAuth = r;
//     cred = firebase.auth.GoogleAuthProvider.credential(r.currentUser.Nb.wc.id_token);
//     firebase.auth().signInWithCredential(cred).then(lgn=>{
//       console.log(lgn);
//     });
//     // const token = guser.getAuthResponse().id_token;
//     // const cred = firebase.auth.GoogleAuthProvider.credential(token);
//     // await firebase.auth().signInAndRetrieveDataWithCredential(cred);
//   })
// }
function handleClientLoad() {
      gapi.load('client:auth2', initClient);
}
    </script>
