
myApp.filter("isRecurring",function(){
    return function(input){
        return (input == 1) ? "Yes" : "No";
    };
});