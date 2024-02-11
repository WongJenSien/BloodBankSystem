const toogleBTN = document.querySelector("#toggle-btn");

toogleBTN.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("expand");
});

function dateRange(){
    var today = new Date();

    // Set the minimum date to today
    var minDate = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD

    // Set the maximum date to one week from today
    var nextWeek = new Date(today);
    nextWeek.setDate(nextWeek.getDate() + 365);
    var maxDate = nextWeek.toISOString().split('T')[0]; // Format: YYYY-MM-DD

    // Set the min 
    document.getElementById("expiredDate_A_P").setAttribute('min', minDate);
    document.getElementById("expiredDate_A_N").setAttribute('min', minDate);
    
    document.getElementById("expiredDate_B_P").setAttribute('min', minDate);
    document.getElementById("expiredDate_B_N").setAttribute('min', minDate);

    document.getElementById("expiredDate_O_P").setAttribute('min', minDate);
    document.getElementById("expiredDate_O_N").setAttribute('min', minDate);

    document.getElementById("expiredDate_AB_P").setAttribute('min', minDate);
    document.getElementById("expiredDate_AB_N").setAttribute('min', minDate);

    // Set the max
    document.getElementById("expiredDate_A_P").setAttribute('max', maxDate);
    document.getElementById("expiredDate_A_N").setAttribute('max', maxDate);
    
    document.getElementById("expiredDate_B_P").setAttribute('max', maxDate);
    document.getElementById("expiredDate_B_N").setAttribute('max', maxDate);

    document.getElementById("expiredDate_O_P").setAttribute('max', maxDate);
    document.getElementById("expiredDate_O_N").setAttribute('max', maxDate);

    document.getElementById("expiredDate_AB_P").setAttribute('max', maxDate);
    document.getElementById("expiredDate_AB_N").setAttribute('max', maxDate);
    
}

function countBlood(){
    var aPositive = parseInt(document.getElementById("aPositive").value);
    var aNegative = parseInt(document.getElementById("aNegative").value);

    var bPositive = parseInt(document.getElementById("bPositive").value);
    var bNegative = parseInt(document.getElementById("bNegative").value);

    var oPositive = parseInt(document.getElementById("oPositive").value);
    var oNegative = parseInt(document.getElementById("oNegative").value);

    var abPositive = parseInt(document.getElementById("abPositive").value);
    var abNegative = parseInt(document.getElementById("abNegative").value);

    var totalA = aPositive + aNegative;
    var totalB = bPositive + bNegative;
    var totalO = oPositive + oNegative;
    var totalAB = abPositive + abNegative;

    document.getElementById('labelA').innerText = totalA;
    document.getElementById('labelB').innerText = totalB;
    document.getElementById('labelO').innerText = totalO;
    document.getElementById('labelAB').innerText = totalAB;
    
}

function getShipDate(){
    const date = new Date();
    let d = date.getDate();
    let m = date.getMonth() + 1;
    let y = date.getFullYear();

    if(d < 10){
        d = '0' + d;
    }
    if(m < 10){
        m = '0' + m;
    }

    let current = y + "-" + m + "-" + d;
document.getElementById("ship-date").setAttribute('min', current);
}