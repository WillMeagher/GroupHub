var MAX_NAME_LEN = 20;
var logged_in = true;
var user_first = "William";
var user_last = "Meagher"
var num_notifications = (99);

function load(){
  //set_background_height();
  //set_notifications();
}

//set notifications position on load
function set_notifications(){
  // check to see if we need to show notifications
  if (num_notifications > 0){
    // check to see what to put for num_notifications
    if (num_notifications > 99) {
      document.getElementById("num_notifications").innerHTML = "99+";
    } else {
      document.getElementById("num_notifications").innerHTML = num_notifications;
    }
    document.getElementById("num_notifications").style.visibility = "show";
  } else {
    document.getElementById("num_notifications").style.visibility = "hidden";
  }

  // this needs to match the css value for max width
  if(window.innerWidth > 1450) {
    document.getElementById("header-dropdown").style.visibility = "hidden";
    notification_header();
  } else {
    if (document.getElementById("header-dropdown").style.visibility !== "hidden") {
      notification_dropdown();
    } else {
      notification_menu();
    }
  }
}

function reset_notifications(){
  num_notifications = 0;
}

//Notification functions
function notification_menu(){
  var notifi = document.getElementById("num_notifications")
  notifi.style.position = "relative";
  notifi.style.top = "10px";
  notifi.style.right = "-44px";
  notifi.style.margin = "0 0 0 auto";
}

function notification_dropdown(){
  var notifi = document.getElementById("num_notifications")
  notifi.style.position = "absolute";
  notifi.style.top = "278px";
  notifi.style.right = "408px";
  notifi.style.margin = "0";
}

function notification_header(){
  var notifi = document.getElementById("num_notifications")
  notifi.style.position = "relative";
  notifi.style.top = "0";
  notifi.style.right = "0";
  notifi.style.margin = "10px 0 0 205px";
}

function set_login_register(){
  if(!logged_in){
    if(window.innerWidth < 700) {
      document.getElementById("login-register").style.visibility = "hidden";
    } else{
      document.getElementById("login-register").style.visibility = "visible";
    }
  }
}

function set_background_height(){
  var body_height = document.getElementById("content").offsetHeight;
  var background_img = document.getElementById("background-image").offsetHeight;
  var background_height = Math.max(body_height - background_img + 15, 0);
  document.getElementById("blue-backdrop").style.height = background_height + "px";
  if (background_height > 0) {
    document.getElementById("shapes").style.visibility = "visible";
  } else {
    document.getElementById("shapes").style.visibility = "hidden";
  }
}

// constantly update where the notificaitons are when window size is changing
addEventListener('resize', function(event){
  if (document.getElementById("num_notifications") != null) {
    //set_notifications();
  }
  //set_background_height();
});