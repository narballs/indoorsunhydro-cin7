<script>
        $(document).ready(function () {
            var timeSpanToCancel = $("#timeSpanToCancel").val();
            //alert()
            var timeSpanToCancel =  new Date(timeSpanToCancel);
            //alert(timeSpanToCancel);
            var currentTime = new Date();
            var currentTimeStamp = new Date();
            var day = currentTimeStamp.getDate();
            var month = currentTimeStamp.getMonth() + 1;
            var year = currentTimeStamp.getFullYear();
            var time = year + "-" + month + "-"+ day +" "+currentTimeStamp.getMonth() +":"+currentTimeStamp.getHours() + ":" + currentTimeStamp.getMinutes() + ":" + currentTimeStamp.getSeconds();
           //var canceltime = new Date(timeSpanToCancel);
            //var time_diff = new Date(canceltime) - new Date(currentTimeStamp);
            //var t = new Date(time_diff);
           // alert(t.getMinutes);
           //long diff = d2.getTime() - d1.getTime();
    
            var time_diff =  currentTime  - timeSpanToCancel;
            var minutes = new Date(time_diff).getMinutes(); 
            var seconds = new Date(time_diff).getSeconds(); 
            console.log(minutes);
            console.log(seconds);
            var timer2 = minutes +":"+ seconds;
            console.log(timer2);
            var interval = setInterval(function() {


            var timer = timer2.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 0) clearInterval(interval);
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('#cancel_order').val('Cancel Order in '+ minutes + ':' + seconds);
            timer2 = minutes + ':' + seconds;
               //console.log(minutes);
            //console.log(seconds);
            }, 1000);

        });
