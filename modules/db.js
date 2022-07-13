function noAPILogIn() {
  var provider = new firebase.auth.GoogleAuthProvider();
  firebase.auth().signInWithRedirect(provider).then(r=>{
    loadDetails();
  })
}

function loadDetails(defaultDoc,callback,collection) {
  var uid = firebase.auth().currentUser.uid;
  db.collection(collection).doc(uid).onSnapshot(r =>{
    if(r.data()){
      callback(r.data())
    }
    else {
      db.collection(collection).doc(uid).set(defaultDoc);
    }
  })
}
function addToMap(collection,obj) {
  var uid = firebase.auth().currentUser.uid;
  db.collection(collection).doc(uid).set(obj)
}
//////////////////////////////////////////////
var name = "db";
var lib = {
  login: noAPILogIn,
  load:loadDetails,
  add:addToMap
}
export {name,lib};
