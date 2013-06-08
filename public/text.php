<!--<html>
    <head>
        <title>
            BullShit
        </title>
        <style>
            #test { display: none}
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    </head>
    <body>
        <p>
            <button id="btnClick">Cick me!</button> 
        </p>
        <p>
            Second paragraph 
        </p>
        <div id="test" >
            asdfjldsjkflj asdflkajsdfljasdlfkjasldf asdfalksdjflasdf
            <br/>
            asdfasdf
            asdf,<br/>
            asdfsdf
        </div>
        <script>
        $(document).ready(function(){
            //$("p").html("page");
            
            $("p:eq(0) button").click(function(){
               $("#test").slideDown();
            })
        });
    

        </script>
    </body>
    
 </html>-->
<html>
    <head>
        <title> Calculator </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <style>
            .button {
                border-top: 1px solid #96d1f8;
                background: #65a9d7;
                background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
                background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
                background: -moz-linear-gradient(top, #3e779d, #65a9d7);
                background: -ms-linear-gradient(top, #3e779d, #65a9d7);
                background: -o-linear-gradient(top, #3e779d, #65a9d7);
                padding: 7.5px 15px;
                -webkit-border-radius: 6px;
                -moz-border-radius: 6px;
                border-radius: 6px;
                -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
                -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
                box-shadow: rgba(0,0,0,1) 0 1px 0;
                text-shadow: rgba(0,0,0,.4) 0 1px 0;
                color: white;
                font-size: 13px;
                font-family: Georgia, serif;
                text-decoration: none;
                vertical-align: middle;
            }
            .button:hover {
                border-top-color: #28597a;
                background: #28597a;
                color: #ccc;
            }
            .button:active {
                border-top-color: #1b435e;
                background: #1b435e;
            }
            .div{
                border-top: 1px solid #96d1f8;
                background: #e4ebf0;

                padding: 7.5px 15px;
                -webkit-border-radius: 6px;
                -moz-border-radius: 6px;
                border-radius: 6px;
                -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
                -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
                box-shadow: rgba(0,0,0,1) 0 1px 0;
                text-shadow: rgba(0,0,0,.4) 0 1px 0;
                color: black;
                font-size: 13px;
                font-family: Georgia, serif;
                text-decoration: none;
                vertical-align: middle; 




            }
            .box{
                width: 200;  
            }
        </style>
    </head>
    <body>
        <div class="box">
            <div style="width:180px; margin:0 auto">
                <input id="in" class="div" type="text" style="width:100%" />
                <span> </span>
            </div>

            <table style="width:100%">
                <tr>
                    <td><button class="button number"> 1 </button></td>
                    <td><button class="button number"> 2 </button></td>
                    <td><button class="button number"> 3 </button></td>
                </tr>
                <tr>
                    <td><button class="button number"> 4 </button></td>
                    <td><button class="button number"> 5 </button></td>
                    <td><button class="button number"> 6 </button></td>
                </tr>
                <tr>
                    <td><button class="button number"> 7 </button></td>
                    <td><button class="button number"> 8 </button></td>
                    <td><button class="button number"> 9 </button></td>
                </tr>
                <tr>
                    <td><button class="button number"> 0 </button></td>
                    <td><button class="button operator"> + </button></td>
                    <td><button class="button operator"> - </button></td>
                </tr>
                <tr>
                    <td><button class="button operator"> * </button></td>
                    <td><button class="button operator"> / </button></td>
                    <td><button class="button answer">   = </button></td>
                </tr>
            </table>
        </div>
        <script>
            $(document).ready(function(){
                
              
              
                
                var firstarr = []; 
                var secondarr = [];
                var sign = 0;
                $("button").click(function(){
                    var what = $(this).attr("class");
                    
                    if(what == "button number"){
                        
                        if(sign == 0){
                            firstarr[firstarr.length] = $(this).text().trim();
                            var firstnumber = "";
                            jQuery.each(firstarr, function(k,v){
                                var temp = v;
                                firstnumber = firstnumber+temp; 
                       
                            });
                            $("input:text").val(firstnumber);
                        }// if sign = empty string 
                        else{
                            
                            secondarr[secondarr.length] = $(this).text().trim();
                            var secondnumber = "";
                            jQuery.each(secondarr, function(k,v){
                                var temp = v;
                                secondnumber = secondnumber+temp; 
                       
                            });
                            $("input:text").val(secondnumber);
                        } // else statement 
                    }// if's what statement
                    else if(what == "button operator"){
                        sign = $(this).text().trim();
                        $("input:text"). val(sign);
                    }
                    else{
                        var firstvalue = 0;
                        var secondvalue = 0;
                        
                    
                        jQuery.each(firstarr, function(k,v){
                            var pow = firstarr.length - (k+1);
                               
                            var value = v*(Math.pow(10,pow));
                            firstvalue  = firstvalue + value; 
                                
                       
                        });
                        jQuery.each(secondarr, function(k,v){
                            var powr = secondarr.length - (k+1);
                            var valuetwo = v*(Math.pow(10,powr));
                            secondvalue  = secondvalue + valuetwo; 
                        });
                        if(sign == "+"){
                            $("input:text").val(firstvalue + secondvalue);
                            
                        }else if(sign == "-"){
                            $("input:text").val(firstvalue - secondvalue);
                        }else if(sign == "*"){
                            $("input:text").val(firstvalue * secondvalue);
                        }else if(sign == "/"){
                            $("input:text").val(firstvalue / secondvalue);
                        }

                        
                                
                            
                        firstarr =[];
                        secondarr = [];
                        sign = 0;
                           
                    }// end of else
                   
                    
                });//end of button click  
                
            });//end of document.
            /*firstarr[firstarr.length] = $(this).text().trim();
                            var firstnumber = "";
                            jQuery.each(firstarr, function(k,v){
                                var temp = v;
                                firstnumber = firstnumber+temp; 
                       
                            });
                            $("input:text").val(firstnumber);*/
           
        </script>
    </body>
</html>
