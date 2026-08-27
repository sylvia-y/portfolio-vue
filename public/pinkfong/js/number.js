$(function(){
        counting();
        function counting(){
            let memberCountConTxt= 111784874;
            $({ val : 0 }).animate({ val : memberCountConTxt }, {
            duration: 3500,
            step: function() {
                var num = numberWithCommas(Math.floor(this.val));
                $(".count1").text(num);
            },
            complete: function() {
                var num = numberWithCommas(Math.floor(this.val));
                $(".count1").text(num);
            }
            });
    
            memberCountConTxt= 59873384505;
            $({ val : 0 }).animate({ val : memberCountConTxt }, {
            duration: 3500,
            step: function() {
            var num = numberWithCommas(Math.floor(this.val));
            $(".count2").text(num);
            },
            complete: function() {
            var num = numberWithCommas(Math.floor(this.val));
            $(".count2").text(num);
            }
            });
        }
        
        //3자리마다 , 찍기
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }



});
