function validate(){
    var fname = document.serach.empfname.value;
    if(fname == "") {
        alert("First name should not be Blank !");
        return false;
    }
    //alert(fname);

return true;
}